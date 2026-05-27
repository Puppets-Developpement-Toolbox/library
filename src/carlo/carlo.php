<?php

use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;
use carlo\DriverInterface;
use carlo\FileNotFoundException;

global $CARLO_CONTEXT;
$CARLO_CONTEXT = [];

const CARLO_BASEPATH = __DIR__ . "/../";

function carlo_register($structure_path)
{
    carlo_driver()->register($structure_path);
}

/**
 * Enregistrer un namespace avec son chemin
 *
 * @param string $namespace Nom du namespace (ex: 'restaurant')
 * @param string $path Chemin absolu vers le dossier du namespace
 */
function carlo_register_namespace(string $namespace, string $path)
{
    carlo_driver()->registerNamespace($namespace, $path);
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

function carlo_structure($type, $name = null, $variant = "base", $namespace = 'default')
{
    return carlo_driver()->structure($type, $name, $variant, $namespace);
}

/**
 * Décompose un ID de template en ses parties
 * Exemples :
 * - "sections/image:base" → ["sections", "image", "base", "default"]
 * - "@restaurant:sections/image" → ["sections", "image", "base", "restaurant"]
 * - "@restaurant:sections/image:variant" → ["sections", "image", "variant", "restaurant"]
 */
function carlo_explode_id($element_id)
{
    $namespace = 'default';

    // 1. Extraction du namespace si présent (@restaurant:...)
    if (strpos($element_id, '@') === 0) {
        list($namespace_raw, $element_id) = explode(':', $element_id, 2);
        $namespace = substr($namespace_raw, 1); // Enlever le @
    }

    // 2. Extraction du type et du nom (sections/image)
    list($template_type, $template_name) = explode("/", "{$element_id}/");

    if (empty($template_name)) {
        $template_name = $template_type;
        $template_type = "";
    }

    // 3. Extraction de la variante (:base, :text, etc.)
    list($template_name, $template_variant) = explode(":", $template_name . ":base");

    return [$template_type, $template_name, $template_variant, $namespace];
}

function carlo_render($tpl, $args = [])
{
    return carlo_driver()->render($tpl, $args);
}

function carlo_get($key = null)
{
    return carlo_driver()->get($key);
}

function carlo_load_data($structure, $args)
{
    carlo_driver()->loadData($structure, $args);
}

function carlo_get_file($type, $element, $variant = "base", $namespace = 'default')
{
    return carlo_driver()->getFile($type, $element, $variant, $namespace);
}

function carlo_context_add($key, $value)
{
    global $CARLO_CONTEXT;
    end($CARLO_CONTEXT);
    $contextLastKey = key($CARLO_CONTEXT);
    $CARLO_CONTEXT[$contextLastKey][$key] = $value;
}

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

function carlo_img($key)
{
    $source_sizes = [];
    $args = func_get_args();

    array_shift($args);

    $imgAttrs = [];
    $lastArg = end($args);
    if (is_array($lastArg) && array_key_exists("class", $lastArg)) {
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

    if(isset($component['acf_fc_layout'])){
        $component["_id"] = $component['acf_fc_layout'];
    }

    return carlo_render($component["_id"], $component);
}

// --- Introspection API ---

/**
 * Get all available sections with optional metadata
 *
 * @param bool $includeMetadata Include full metadata for each section
 * @return array
 */
function carlo_get_available_sections(bool $includeMetadata = false): array
{
    return carlo_driver()->introspect()->getAllElements('sections', $includeMetadata);
}

/**
 * Get all available components with optional metadata
 *
 * @param bool $includeMetadata Include full metadata for each component
 * @return array
 */
function carlo_get_available_components(bool $includeMetadata = false): array
{
    return carlo_driver()->introspect()->getAllElements('components', $includeMetadata);
}

/**
 * Get metadata for a specific element
 *
 * @param string $id Element ID (e.g., 'sections/text:simple')
 * @return array|null
 */
function carlo_get_element_info(string $id): ?array
{
    return carlo_driver()->introspect()->getElementMetadata($id);
}

/**
 * List all registered namespaces
 *
 * @return array ['default' => '/path', 'custom' => '/path', ...]
 */
function carlo_list_namespaces(): array
{
    return carlo_driver()->introspect()->getRegisteredNamespaces();
}

/**
 * Scan available elements of a specific type in a namespace
 *
 * @param string $type Type (sections, components, etc.)
 * @param string $namespace Namespace to scan
 * @return array List of element IDs
 */
function carlo_scan_elements(string $type, string $namespace = 'default'): array
{
    return carlo_driver()->introspect()->scanAvailableElements($type, $namespace);
}

/**
 * Get all variants for a specific element
 *
 * @param string $type Type (sections, components)
 * @param string $name Element name
 * @param string $namespace Namespace
 * @return array List of variant names
 */
function carlo_get_element_variants(string $type, string $name, string $namespace = 'default'): array
{
    return carlo_driver()->introspect()->getElementVariants($type, $name, $namespace);
}
