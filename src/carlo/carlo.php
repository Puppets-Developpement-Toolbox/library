<?php
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;
use carlo\DriverInterface;

// une pile stockant les variables contextuelles ajoutées
// par un parent et accessible à dans les enfants
global $CARLO_CONTEXT;
$CARLO_CONTEXT = [];

const CARLO_BASEPATH = __DIR__ . "/../";

function carlo_register($structure_path)
{
    carlo_driver()->register($structure_path);
}

function carlo_driver(DriverInterface $driver = null)
{
    static $_driver;
    if ($driver === null && !isset($_driver)) {
        throw new InvalidArgumentException(
            "Impossible d utiliser carlo sans driver"
        );
    }
    if ($driver) {
        $_driver = $driver;
    }
    return $_driver;
}

/**
 * load structure definition from structure.yml
 */
function carlo_structure($type, $name = null)
{
    return carlo_driver()->structure($type, $name);
}

function carlo_explode_id($element_id)
{
    list($template_type, $template_name) = explode("/", "{$element_id}/");

    if (empty($template_name)) {
        $template_name = $template_type;
        $template_type = "";
    }

    list($template_name, $template_variant) = explode(
        ":",
        $template_name . ":"
    );
    return [$template_type, $template_name, $template_variant];
}
/**
 * load and return the template and inject args
 */
function carlo_render($tpl, $args = [])
{
    return carlo_driver()->render($tpl, $args);
}

/**
 * return the value of key from local args or context
 */
function carlo_get($key = null)
{
    return carlo_driver()->get($key);
}

/**
 * load data for the current template
 */
function carlo_load_data($structure)
{
    carlo_driver()->loadData($structure);
}

/**
 * include the given file
 */
function carlo_get_file($type, $element, $variant = "base")
{
    $variant = $variant ?: "base";
    $abspath = CARLO_BASEPATH . "templates/{$element}";
    $ext = $type === "structure" ? "yml" : "php";

    $paths = [
        "{$abspath}/{$variant}.{$ext}",
        "{$abspath}/base.{$ext}",
        "{$abspath}.{$ext}",
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            if (strpos(realpath($path), realpath(CARLO_BASEPATH)) === false) {
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
