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
 * returns html image
 *
 * exemple :
 * - carlo_img(1) => return img tag for img with id 1 in its original format
 * - carlo_img(1, '70x70') => return same img in 70x70 format
 * - carlo_img(1, ['70x70', '(min-width: 1024px)' => '1600x900']) => return same image with an other dimension for big screen
 * - carlo_img(1, ['70x70', '(min-width: 1024px)' => '1600x900'], 2, [(min-width: 2024px)' => '1600x900']) => return same image and image with id 2 for really big screen
 * - carlo_img(1, ['class' => 'mr-16']) => in any case, if the last argument is an array with class key this is added as img attributes
 */
function carlo_img($key)
{
    $source_sizes = [];
    $args = func_get_args();

    // remove first arg, it's the id
    array_shift($args);

    $imgAttrs = [];
    $lastArg = end($args);
    if (is_array($lastArg) && array_key_exists("class", $lastArg)) {
        // last arg is img attrs
        $imgAttrs = $lastArg;
        array_pop($args);
    }

    $dimensions = array_shift($args);
    $default_size = null;
    $source_sizes = [];
    if (is_string($dimensions) && !empty($dimensions)) {
        $dimensions = [$dimensions];
    }
    if (is_array($dimensions)) {
        $default_size = $dimensions[0];
        unset($dimensions[0]);
        $source_sizes = $dimensions;
    }

    $mobile_key = array_shift($args);
    $mobile_source_sizes = array_shift($args) ?? [];

    return carlo_driver()->img(
        $key,
        $default_size,
        $source_sizes,
        $mobile_key,
        $mobile_source_sizes,
        $imgAttrs
    );
}

function carlo_component($component)
{
    if (is_string($component)) {
        $component = carlo_get($component);
    }
    return carlo_render($component["_id"], $component);
}
