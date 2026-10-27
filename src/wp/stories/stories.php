<?php

/**
 * Catalogue des composants (équivalent Storybook), chargé hors production uniquement (cf. carlo.php).
 *
 * - /?carlo-stories : catalogue, avec &id=<élément>&story=<exemple> pour en afficher un ;
 * - /?carlo-stories=frame&id=<élément>&story=<exemple> : rendu seul d'un exemple (iframe du catalogue).
 *
 * Les éléments sont découverts par l'introspection de la library (carlo_get_available_sections() /
 * carlo_get_available_components()). Les exemples sont rédigés dans <variante>.stories.yml, à côté du
 * template, dans un des dossiers de recherche du driver (thème, plugin, library). Un élément sans
 * exemple est affiché avec un contenu généré d'après ses champs.
 */

use Symfony\Component\Yaml\Yaml;

/**
 * Driver des exemples : une image peut y être une dimension (1200x800), rendue par une image de
 * remplacement locale.
 *
 * Avec $placeholders (élément sans exemple), les champs non renseignés reçoivent un contenu généré
 * d'après leur type. Sans, ils restent vides : un exemple qui omet un champ le veut vide.
 */
class CarloStoriesDriver extends CarloWpDriver
{
    // Court pour les champs texte (souvent des titres), long pour les champs riches.
    private const LOREM_SHORT = 'Lorem ipsum dolor sit amet';
    private const LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

    public bool $placeholders = false;

    protected function loadValue(array $field, mixed $value, carlo\LoadContext $ctx): mixed
    {
        if ($this->placeholders && $value === null) {
            $value = $this->placeholder($field);
        }
        return parent::loadValue($field, $value, $ctx);
    }

    // Les éléments obligatoires (min), complétés jusqu'à trois pour voir la mise en page se répéter, dans la limite de max.
    protected function loadRepeater(array $field, mixed $value, carlo\LoadContext $ctx): array
    {
        if ($this->placeholders && $value === null) {
            $count = max($field['min'], 3);
            $value = array_fill(0, $field['max'] ? min($count, $field['max']) : $count, []);
        }
        return parent::loadRepeater($field, $value, $ctx);
    }

    /**
     * Contenu généré d'un champ ; null laisse la valeur par défaut du type (booléen, types non
     * éditables, groupes et répéteurs, dont les sous-champs sont générés à leur tour).
     */
    private function placeholder(array $field): mixed
    {
        return match ($field['type']) {
            'text_short' => $field['label'],
            'text' => self::LOREM_SHORT,
            'wysiwyg' => self::LOREM,
            'url' => '#',
            'number' => 1,
            'image' => '1200x800',
            'choices' => $field['multi'] ? [(string) array_key_first($field['choices'])] : (string) array_key_first($field['choices']),
            'reference' => get_posts([
                'post_type' => $field['post_types'],
                'numberposts' => min($field['max'] ?: 3, 3),
                'fields' => 'ids',
            ]),
            default => null,
        };
    }

    public function img(string $key, string $default_size, array $source_sizes, $mobile_key, array $mobile_source_sizes, array $imgAttrs)
    {
        $value = is_numeric($key) ? $key : $this->get($key);
        if (is_string($value) && preg_match('/^(\d+)x(\d+)$/', $value, $size)) {
            [, $width, $height] = $size;
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}"><rect width="100%" height="100%" fill="#d6d6d6"/><text x="50%" y="50%" fill="#555" font-family="sans-serif" font-size="32" text-anchor="middle" dominant-baseline="middle">{$width} × {$height}</text></svg>
SVG;
            $attrs = '';
            foreach ($imgAttrs as $name => $attr) {
                $attrs .= ' ' . esc_attr($name) . '="' . esc_attr($attr) . '"';
            }
            return '<img src="data:image/svg+xml,' . rawurlencode($svg) . "\" width=\"{$width}\" height=\"{$height}\" alt=\"\"{$attrs}>";
        }
        return parent::img($key, $default_size, $source_sizes, $mobile_key, $mobile_source_sizes, $imgAttrs);
    }
}

/**
 * Éléments du catalogue, groupés (sections, composants) et indexés par id d'introspection.
 */
function carlo_stories_elements()
{
    static $elements;
    if (isset($elements)) {
        return $elements;
    }

    $elements = [];
    foreach (['sections' => carlo_get_available_sections(true), 'components' => carlo_get_available_components(true)] as $group => $items) {
        foreach ($items as $id => $metadata) {
            [$type, $name, $variant] = carlo_explode_id($id);
            $element = [
                'id' => $id,
                'name' => $name,
                'variant' => $variant,
                'title' => carlo_section_title($id, $metadata),
                'fields' => [],
                'stories' => [],
                'stories_from_base' => false,
                'stories_file' => "templates/{$type}/{$name}/{$variant}.stories.yml",
                'errors' => [],
            ];
            // Une définition invalide ne doit pas empêcher d'afficher le reste du catalogue.
            try {
                $element['fields'] = carlo\Fields::of($metadata);
            } catch (Throwable $e) {
                $element['errors'][] = "Champs : {$e->getMessage()}";
            }
            try {
                [$element['stories'], $element['stories_from_base']] = carlo_stories_load($id);
            } catch (Throwable $e) {
                $element['errors'][] = "Exemples : {$e->getMessage()}";
            }
            $elements[$group][$id] = $element;
        }
    }
    return $elements;
}

function carlo_stories_find($id)
{
    foreach (carlo_stories_elements() as $items) {
        if (isset($items[$id])) {
            return $items[$id];
        }
    }
    return null;
}

/**
 * Exemples d'un élément, résolus par getFile() comme la structure et le template : <variante>, puis base.
 * Renvoie les exemples et si ceux-ci sont hérités de base (variante sans .stories.yml propre).
 */
function carlo_stories_load($id)
{
    [$type, $name, $variant, $namespace] = carlo_explode_id($id);
    try {
        $file = carlo_driver()->getFile('stories', "{$type}/{$name}", $variant, $namespace);
    } catch (carlo\FileNotFoundException $e) {
        return [[], false];
    }
    $stories = array_filter((array) Yaml::parseFile($file), 'is_array');
    return [$stories, $variant !== 'base' && basename($file) !== "{$variant}.stories.yml"];
}

/**
 * Champs à plat pour le tableau du catalogue : chemin (quote.author, cards.*.title) => champ.
 */
function carlo_stories_field_rows($fields, $path = '')
{
    $rows = [];
    foreach ($fields as $key => $field) {
        $rows[$path . $key] = $field;
        if (isset($field['fields'])) {
            $rows += carlo_stories_field_rows($field['fields'], $path . $key . ($field['type'] === 'repeater' ? '.*.' : '.'));
        }
    }
    return $rows;
}

/**
 * URL du catalogue ou d'un rendu seul.
 */
function carlo_stories_url($args = [])
{
    return add_query_arg(array_map('rawurlencode', $args), home_url('/?carlo-stories'));
}

function carlo_stories_frame($element, $story)
{
    // Driver des exemples, avec les namespaces enregistrés par le thème.
    $driver = new CarloStoriesDriver();
    $driver->placeholders = !$element['stories'];
    foreach (carlo_driver()->getNamespaces() as $namespace => $path) {
        $driver->registerNamespace($namespace, $path);
    }
    $driver->register(get_stylesheet_directory() . '/structure.yml');
    carlo_driver($driver);

    show_admin_bar(false);
    include __DIR__ . '/templates/frame.php';
}

// Accès depuis l'admin : Apparence > Catalogue. Une URL complète en slug sert directement de lien.
add_action('admin_menu', function () {
    add_submenu_page('themes.php', 'Catalogue des composants', 'Catalogue', 'edit_theme_options', carlo_stories_url());
});

add_action('template_redirect', function () {
    if (!isset($_GET['carlo-stories'])) {
        return;
    }
    header('X-Robots-Tag: noindex, nofollow');

    // Seuls les éléments découverts peuvent être rendus : pas de template arbitraire depuis l'URL.
    $element = carlo_stories_find(wp_unslash($_GET['id'] ?? ''));
    $story_key = wp_unslash($_GET['story'] ?? '');

    if ($_GET['carlo-stories'] === 'frame') {
        if (!$element) {
            status_header(404);
            exit('Élément inconnu.');
        }
        // Sans exemple demandé (ou inconnu) : le premier, comme dans le catalogue.
        carlo_stories_frame($element, $element['stories'][$story_key] ?? reset($element['stories']) ?: []);
    } else {
        include __DIR__ . '/templates/catalogue.php';
    }
    exit;
});
