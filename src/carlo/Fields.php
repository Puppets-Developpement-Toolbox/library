<?php

namespace carlo;

use Exception;

/**
 * Champs d'un élément, sous une forme commune au PHP (données des templates, attributs de bloc) et à
 * l'éditeur de WordPress (window.carloSections) : type, libellé, aide, options, sous-champs et valeur
 * par défaut.
 */
final class Fields
{
    /**
     * Champs d'une définition : ses clés qui ne commencent pas par « _ ».
     */
    public static function of(?array $definition): array
    {
        $fields = [];
        foreach ($definition ?? [] as $key => $field) {
            if (!str_starts_with((string) $key, '_')) {
                $fields[$key] = self::field((string) $key, $field);
            }
        }
        return $fields;
    }

    public static function field(string $key, mixed $definition): array
    {
        if (empty($definition)) {
            throw new Exception("le champ {$key} n'a pas de définition");
        }

        $type = is_string($definition)
            ? $definition
            : $definition['_type'] ?? (isset($definition['_id']) ? 'group' : throw new Exception("le champ {$key} n'a pas de type (_type)"));
        $definition = (array) $definition;

        $type = match (true) {
            $type === 'select' => 'choices', // @deprecated
            $type === 'image' && !empty($definition['_multi']) => 'gallery',
            default => $type,
        };

        $field = [
            'type' => $type,
            'label' => html_entity_decode($definition['_label'] ?? str_replace('_', ' ', ucfirst($key))),
        ];

        if (isset($definition['_help'])) {
            $field['help'] = html_entity_decode($definition['_help']);
        }

        // Nom sous lequel le champ se relit ailleurs (cf. BaseDriver::getExposed()).
        if (isset($definition['_expose'])) {
            $field['expose'] = $definition['_expose'];
        }

        if ($type === 'group') {
            // Composant chargé par !load : son _id vient de sa structure.
            $field['id'] = $definition['_id'];
            $field['fields'] = self::of($definition);
        }

        if ($type === 'repeater') {
            $field['fields'] = self::of($definition['_repeat']);
            $field['min'] = $definition['_min'] ?? $definition['_times'] ?? 0;
            $field['max'] = $definition['_max'] ?? $definition['_times'] ?? null;
            $field['add_button'] = $definition['_add_button'] ?? 'Ajouter un élément';
        }

        if ($type === 'choices') {
            $field['choices'] = $definition['_choices'];
            $field['multi'] = !empty($definition['_multi']);
        }

        if ($type === 'reference') {
            $field['post_types'] = (array) $definition['_ref_type'];
            $field['max'] = $definition['_max'] ?? null;
        }

        $field['default'] = match (true) {
            $type === 'boolean' => false,
            in_array($type, ['group', 'repeater', 'gallery', 'reference'], true) => [],
            $type === 'choices' && $field['multi'] => [],
            default => '',
        };

        return $field;
    }
}
