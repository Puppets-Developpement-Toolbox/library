<?php
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\ErrorHandler\Debug;

include_once __DIR__ . "/vendor/autoload.php";

Debug::enable();

// une pile stockant les variables locals aux templates ouverts
global $CARLO_ARGS;
$CARLO_ARGS = [];
// une pile qui permet de détecter le niveau de profondeur du template
// en cours pour charger des données spécifiques au contexte
global $CARLO_TPL_PATH;
$CARLO_TPL_PATH = [];
// une pile stockant les variables contextuelles ajoutées
// par un parent et accessible à dans les enfants
global $CARLO_CONTEXT;
$CARLO_CONTEXT = [];

/**
 * load structure definition from structure.yml
 */
function carlo_structure($type, $name)
{
    static $structure;

    if (!isset($structure)) {
        $structure = Yaml::parseFile(
            __DIR__ . "/structure.yml",
            Yaml::PARSE_CUSTOM_TAGS
        );
    }

    try {
        $file = carlo_get_file("structure", "{$type}/{$name}");
        $structure[$type][$name] = Yaml::parseFile(
            $file,
            Yaml::PARSE_CUSTOM_TAGS
        );
        $structure[$type][$name]["_id"] = "{$type}/{$name}";
    } catch (Exception $e) {
    }
    return $structure[$type][$name];
}

/**
 * load and return the template and inject args
 */
function carlo_render($tpl, $args = [])
{
    global $CARLO_ARGS, $CARLO_TPL_PATH, $CARLO_CONTEXT;
    list($template_type, $template_name) = explode("/", "{$tpl}/");

    if (empty($template_name)) {
        $template_name = $template_type;
        $template_type = "";
    }

    list($template_name, $template_variant) = explode(
        ":",
        $template_name . ":"
    );

    $structure = carlo_structure($template_type ?: "templates", $template_name);
    $CARLO_TPL_PATH[] = $template_name;
    $CARLO_CONTEXT[] = [];

    $defaultArgs =
        $template_type === "sections" ? carlo_load_data($structure) : [];

    $CARLO_ARGS[] = array_merge($defaultArgs, $args);

    $file = carlo_get_file(
        "template",
        "{$template_type}/{$template_name}",
        $template_variant
    );

    echo "<!-- begin {$file} -->\n";
    include $file;
    echo "<!-- end {$file} -->\n";
    array_pop($CARLO_ARGS);
    array_pop($CARLO_TPL_PATH);
    array_pop($CARLO_CONTEXT);
}

/**
 * return the value of key from local args or context
 */
function carlo_get($key = null)
{
    global $CARLO_ARGS;
    $tpl_args = end($CARLO_ARGS);

    if ($key === null) {
        return $tpl_args;
    }

    $values = carlo_get_value($key, $tpl_args);

    if ($values === "") {
        $values = carlo_context($key);
    }

    return $values;
}

/**
 * load data for the current template
 */
function carlo_load_data($structure)
{
    static $data;
    if (!isset($data)) {
        $data = Yaml::parseFile(__DIR__ . "/data.yml");
    }
    $fields = array_filter(
        array_keys($structure),
        fn($key) => substr($key, 0, 1) !== "_"
    );
    $structure_data = array_map(function ($fieldname) use ($data, $structure) {
        global $CARLO_TPL_PATH;

        $field = $structure[$fieldname];
        if (is_array($field) && !isset($field["type"])) {
            return carlo_load_data($field);
        }

        if (is_string($field)) {
            $type = $field;
        } elseif ($field instanceof TaggedValue) {
            return carlo_load_data(
                carlo_structure($field->getTag(), $field->getValue())
            );
        } elseif (is_array($field)) {
            $type = $field["type"];
        }

        if ($type == "repeater") {
            return array_fill(
                0,
                $field["times"] ?? $field["min"] + 3,
                carlo_load_data($field["repeat"])
            );
        }

        $try = $CARLO_TPL_PATH;
        do {
            $prefix = implode("--", $try);
            if ($prefix) {
                $prefix .= "--";
            }

            if (isset($data["{$prefix}{$fieldname}"])) {
                return $data["{$prefix}{$fieldname}"];
            }
            if (isset($data["{$prefix}{$type}"])) {
                return $data["{$prefix}{$type}"];
            }
        } while (array_shift($try));
        $context = $CARLO_TPL_PATH;
        $context[] = $fieldname;
        $fieldpath = implode(" > ", $context);

        throw new Exception(
            "Impossible de trouver du contenu pour le champ {$fieldpath} de type {$type}"
        );
    }, array_combine($fields, $fields));

    if (isset($structure["_id"])) {
        $structure_data["_id"] = $structure["_id"];
    }

    return $structure_data;
}

/**
 * include the given file
 */
function carlo_get_file($type, $element, $variant = "base")
{
    $variant = $variant ?: "base";
    $abspath = __DIR__ . "/src/templates/{$element}";
    $ext = $type === "structure" ? "yml" : "php";

    $paths = [
        "{$abspath}/{$variant}.{$ext}",
        "{$abspath}/base.{$ext}",
        "{$abspath}.{$ext}",
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            if (strpos(realpath($path), __DIR__) === false) {
                // par sécurité en interdit de charger un fichier hors du projet
                throw new Exception("Le chemin {$element} est hors du projet");
            }

            return $path;
        }
    }
    throw new Exception(
        "Aucun fichier ne correspond à ce que l'on cherche : {$type} - {$element} - {$variant}"
    );
}

/**
 * add a contextual entry
 */
function carlo_context_add($key, $value)
{
    global $CARLO_CONTEXT;
    end($CARLO_CONTEXT);
    $contextLastKey = key($CARLO_CONTEXT);
    $CARLO_CONTEXT[$contextLastKey][$key] = $value;
}

/**
 * return the value of key fron current context
 */
function carlo_context($key)
{
    global $CARLO_CONTEXT;
    $values = call_user_func_array("array_merge", $CARLO_CONTEXT);
    return carlo_get_value($key, $values);
}

function carlo_get_value($key, $values)
{
    $parts = explode(".", $key);
    while (($values = $values[array_shift($parts)] ?? "") && count($parts)) {
    }
    return $values;
}

/**
 * returns an html image, sized width length
 */
function carlo_img($key, $dimensions)
{
    //https://picsum.photos/3000/1500
    return '<img src="https://picsum.photos/' .
        str_replace("x", "/", $dimensions) .
        '" > ';
}

function carlo_component($component)
{
    return carlo_render($component["_id"], $component);
}
