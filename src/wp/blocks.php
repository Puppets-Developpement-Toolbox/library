<?php

/**
 * remove all attribute's key prefixed by _
 */
function carlo_structure_fields($definition)
{
    $fields = array_filter(
        $definition,
        function ($k) {
            return substr($k, 0, 1) !== "_";
        },
        ARRAY_FILTER_USE_KEY
    );
    return $fields;
}

// Champs saisis directement dans l'aperçu de l'éditeur (cf. carlo_field_values).
const CARLO_INLINE_TYPES = ['text_short', 'text', 'wysiwyg'];

/**
 * Schéma des attributs du bloc : un attribut par champ de premier niveau.
 */
function carlo_block_attributes($fields)
{
    return array_map(fn($field) => ['type' => match ($field['type']) {
        'text_short', 'text', 'wysiwyg', 'url', 'color', 'date', 'datetime', 'embed' => 'string',
        'boolean' => 'boolean',
        'number' => 'number',
        'image', 'file' => 'integer',
        'group' => 'object',
        'repeater', 'gallery', 'reference' => 'array',
        'choices' => $field['multi'] ? 'array' : 'string',
        // Types pas encore pris en charge par l'éditeur : valeur libre.
        default => ['string', 'number', 'boolean', 'array', 'object', 'null'],
    }], $fields);
}

/**
 * Données passées au template : chaque champ est présent (la library ne tolère pas les données
 * absentes) et mis en forme comme le faisait ACF.
 *
 * Avec $path (aperçu de l'éditeur), les textes sont remplacés par un marqueur {{carlo:chemin}}
 * que l'éditeur rend saisissable : le rendu ne dépend pas des textes, il n'est pas recalculé
 * pendant la frappe.
 */
function carlo_field_values($fields, $values, $path = null)
{
    $data = [];
    foreach ($fields as $key => $field) {
        $value = $values[$key] ?? null;
        $marker = $path === null ? null : "{$path}{$key}";

        if ($marker !== null && in_array($field['type'], CARLO_INLINE_TYPES, true)) {
            $data[$key] = "{{carlo:{$marker}}}";
            continue;
        }

        $data[$key] = match ($field['type']) {
            'group' => carlo_field_values($field['fields'], (array) $value, $marker === null ? null : "{$marker}."),
            'repeater' => array_map(
                fn($i) => carlo_field_values($field['fields'], (array) ($value[$i] ?? []), $marker === null ? null : "{$marker}.{$i}."),
                // Aperçu : les éléments obligatoires (_min, _times) sont affichés, pour être remplis.
                $marker === null || count((array) $value) >= $field['min'] ? array_keys((array) $value) : range(0, $field['min'] - 1)
            ),
            'reference' => array_values(array_filter(
                array_map('get_post', (array) $value),
                fn($post) => $post && $post->post_status === 'publish'
            )),
            'gallery' => (array) $value,
            'boolean' => (bool) $value,
            'text' => nl2br($value ?? ''),
            'wysiwyg' => wpautop($value ?? ''),
            default => $value ?? '',
        };
    }
    return $data;
}

/**
 * Données d'une section à partir des attributs de son bloc.
 */
function carlo_section_data($id, $attributes, $preview = false)
{
    $fields = carlo\Fields::of(carlo_sections()[$id]);
    return carlo_field_values($fields, $attributes, $preview ? '' : null);
}

/**
 * Nom du bloc d'une section : sections/text:base → carlo/sections-text.
 */
function carlo_block_name($id)
{
    return 'carlo/' . carlo_block_slug($id);
}

function carlo_block_slug($id)
{
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(preg_replace('/:base$/', '', $id))), '-');
}

/**
 * Sections proposées par les régions, indexées par _id.
 */
function carlo_sections()
{
    static $sections;
    if (isset($sections)) {
        return $sections;
    }

    $sections = [];
    foreach (['page', ...array_keys(carlo_structure('types') ?? [])] as $post_type) {
        foreach (carlo_templates($post_type) as $template) {
            foreach (carlo_structure_fields($template) as $region) {
                $sections += carlo_region($region)['sections'];
            }
        }
    }
    return $sections;
}

function carlo_section_title($id, $definition)
{
    [, $name, $variant] = carlo_explode_id($id);
    return $definition['_label'] ?? ucfirst(str_replace('_', ' ', $variant === 'base' ? $name : "{$name} {$variant}"));
}

/**
 * Aperçu d'une section dans l'éditeur (cf. assets/sections.js), et non rendu du site.
 */
function carlo_is_editor_preview()
{
    return wp_is_serving_rest_request() && str_contains($_SERVER['REQUEST_URI'] ?? '', 'block-renderer');
}

/**
 * Un bloc dynamique par section : ses attributs sont les champs de la section, son rendu est
 * le template Carlo de la section.
 */
function carlo_register_section_blocks()
{
    carlo_editor_asset('carlo-sections', 'sections.js', [
        'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-compose', 'wp-element', 'wp-data', 'wp-core-data',
        'wp-api-fetch', 'wp-url', 'wp-escape-html', 'wp-html-entities',
    ]);
    carlo_editor_asset('carlo-sections', 'sections.css');

    foreach (carlo_sections() as $id => $definition) {
        $fields = carlo\Fields::of($definition);

        register_block_type(carlo_block_name($id), [
            'api_version' => 3,
            'title' => carlo_section_title($id, $definition),
            'category' => 'design',
            // ancestor et non parent : parent l'emporterait sur les allowedBlocks de la région.
            // Une section imbriquée a elle aussi une région pour ancêtre.
            'ancestor' => ['carlo/region'],
            'attributes' => carlo_block_attributes($fields),
            // Les templates Carlo n'appliquent pas de classe CSS saisie dans l'éditeur.
            'supports' => ['html' => false, 'className' => false, 'customClassName' => false],
            'editor_script_handles' => ['carlo-sections'],
            'render_callback' => function ($attributes) use ($id, $fields) {
                $preview = carlo_is_editor_preview();
                $filled = false;
                array_walk_recursive($attributes, function ($value) use (&$filled) {
                    $filled = $filled || !empty($value);
                });
                // Section pas encore remplie : rien sur le site.
                if ($fields && !$filled && !$preview) {
                    return '';
                }
                ob_start();
                carlo_render($id, carlo_section_data($id, $attributes, $preview));
                return ob_get_clean();
            },
        ]);
    }
}

add_action('enqueue_block_editor_assets', function () {
    $sections = [];
    foreach (carlo_sections() as $id => $definition) {
        $sections[carlo_block_name($id)] = [
            'title' => carlo_section_title($id, $definition),
            'fields' => (object) carlo\Fields::of($definition),
        ];
    }
    wp_add_inline_script('carlo-sections', 'window.carloSections = ' . wp_json_encode((object) $sections) . ';', 'before');
    // Le formulaire est dans le panneau Réglages, hors de l'iframe du contenu.
    wp_enqueue_style('carlo-sections');
    // Réglages TinyMCE du site (dont les filtres tiny_mce_before_init) pour les champs wysiwyg.
    wp_enqueue_editor();
});

// Repère des contenus Carlo dans l'éditeur, pour élargir le panneau Réglages (cf. sections.css).
add_filter('admin_body_class', function ($classes) {
    $screen = get_current_screen();
    return $screen && $screen->is_block_editor() && carlo_post_template(get_post()) ? "{$classes} carlo-editor" : $classes;
});
