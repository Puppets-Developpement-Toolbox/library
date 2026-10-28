<?php

/**
 * Champs propres à un type de contenu (clé `meta:` de structure.yml) : un post meta par champ, saisi dans
 * le panneau Document de l'éditeur (cf. assets/sections.js, MetaPanel).
 */

// Types de champs pris en charge : ceux qui tiennent dans une valeur scalaire.
const CARLO_META_TYPES = ['text_short', 'text', 'number', 'boolean', 'choices', 'url'];

/**
 * Champs `meta:` d'un type de contenu, normalisés par Fields (vide si le type n'en déclare pas).
 */
function carlo_meta_fields($post_type)
{
    $definition = (carlo_structure('types') ?? [])[$post_type]['meta'] ?? null;
    $fields = carlo\Fields::of($definition);
    foreach ($fields as $key => $field) {
        if (!in_array($field['type'], CARLO_META_TYPES, true) || !empty($field['multi'])) {
            throw new Exception("le champ meta {$key} du type {$post_type} n'a pas un type pris en charge ({$field['type']})");
        }
    }
    return $fields;
}

add_action('init', function () {
    foreach (array_keys(carlo_structure('types') ?? []) as $post_type) {
        $fields = carlo_meta_fields($post_type);
        $schema = carlo_block_attributes($fields);
        foreach ($fields as $key => $field) {
            register_post_meta($post_type, $key, [
                'single' => true,
                'type' => $schema[$key]['type'],
                'show_in_rest' => true,
                'sanitize_callback' => match ($field['type']) {
                    'url' => 'esc_url_raw',
                    'text' => 'sanitize_textarea_field',
                    'text_short', 'choices' => 'sanitize_text_field',
                    default => null,
                },
                'auth_callback' => fn($allowed, $meta_key, $post_id) => current_user_can('edit_post', $post_id),
            ]);
        }
    }
});

// Définition du panneau pour l'éditeur : lue par MetaPanel dans les réglages de l'éditeur.
add_filter('block_editor_settings_all', function ($settings, $context) {
    $post_type = $context->post->post_type ?? null;
    $fields = $post_type ? carlo_meta_fields($post_type) : [];
    if ($fields) {
        $settings['carloMeta'] = [
            'postType' => $post_type,
            'title' => carlo_structure('types')[$post_type]['meta']['_label'] ?? 'Informations',
            'fields' => (object) $fields,
        ];
    }
    return $settings;
}, 10, 2);
