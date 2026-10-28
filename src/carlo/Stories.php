<?php

namespace carlo;

use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * Éléments du catalogue des composants (équivalent Storybook) et leurs exemples, sans dépendre de
 * WordPress : le catalogue d'un environnement (cf. src/wp/stories.php) s'appuie dessus.
 *
 * Les éléments sont découverts par l'introspection de la library (carlo_get_available_sections() /
 * carlo_get_available_components()). Les exemples sont rédigés dans <variante>.stories.yml, à côté du
 * template, dans un des dossiers de recherche du driver (thème, plugin, library). Un élément sans
 * exemple est affiché avec un contenu généré d'après ses champs (cf. Placeholders).
 */
class Stories
{
    private static ?array $elements = null;

    /**
     * Éléments du catalogue, groupés (sections, composants) et indexés par id d'introspection.
     */
    public static function elements(): array
    {
        if (self::$elements !== null) {
            return self::$elements;
        }

        self::$elements = [];
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
                    $element['fields'] = Fields::of($metadata);
                } catch (Throwable $e) {
                    $element['errors'][] = "Champs : {$e->getMessage()}";
                }
                try {
                    [$element['stories'], $element['stories_from_base']] = self::load($id);
                } catch (Throwable $e) {
                    $element['errors'][] = "Exemples : {$e->getMessage()}";
                }
                self::$elements[$group][$id] = $element;
            }
        }
        return self::$elements;
    }

    public static function find(string $id): ?array
    {
        foreach (self::elements() as $items) {
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
    public static function load(string $id): array
    {
        [$type, $name, $variant, $namespace] = carlo_explode_id($id);
        try {
            $file = carlo_driver()->getFile('stories', "{$type}/{$name}", $variant, $namespace);
        } catch (FileNotFoundException $e) {
            return [[], false];
        }
        $stories = array_filter((array) Yaml::parseFile($file), 'is_array');
        return [$stories, $variant !== 'base' && basename($file) !== "{$variant}.stories.yml"];
    }

    /**
     * Champs à plat pour le tableau du catalogue : chemin (quote.author, cards.*.title) => champ.
     */
    public static function fieldRows(array $fields, string $path = ''): array
    {
        $rows = [];
        foreach ($fields as $key => $field) {
            $rows[$path . $key] = $field;
            if (isset($field['fields'])) {
                $rows += self::fieldRows($field['fields'], $path . $key . ($field['type'] === 'repeater' ? '.*.' : '.'));
            }
        }
        return $rows;
    }
}
