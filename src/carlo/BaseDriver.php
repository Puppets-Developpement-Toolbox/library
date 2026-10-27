<?php

namespace carlo;

use Exception;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

abstract class BaseDriver implements DriverInterface
{
    // Données des templates en cours de rendu ; le niveau racine sert à carlo_get() et à
    // carlo_load_data() hors de tout rendu.
    protected $tplArgs = [[]];
    protected $fields = [];
    protected $projectStructure;
    protected $structure;
    protected $loaded = [];
    protected $namespaces = [];
    protected ?Introspection $introspection = null;

    public function __construct()
    {
        // Namespace par défaut : library puppets
        $this->namespaces['default'] = CARLO_BASEPATH . 'templates';
    }

    /**
     * Get introspection instance for discovering available elements
     */
    public function introspect(): Introspection
    {
        if ($this->introspection === null) {
            $this->introspection = new Introspection($this);
        }

        return $this->introspection;
    }

    /**
     * Get all registered namespaces
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Dossiers où chercher les templates d'un namespace, du plus prioritaire au moins prioritaire.
     * Un driver qui surcharge getFile() (thème WordPress…) doit renvoyer les mêmes dossiers.
     */
    public function getSearchPaths(string $namespace = 'default'): array
    {
        return isset($this->namespaces[$namespace]) ? [$this->namespaces[$namespace]] : [];
    }

    /**
     * Enregistrer un namespace avec son chemin
     */
    public function registerNamespace(string $namespace, string $path)
    {
        // Normaliser le chemin (enlever le trailing slash)
        $path = rtrim($path, '/');

        // Vérifier que le chemin existe
        if (!is_dir($path)) {
            throw new Exception("Le chemin du namespace '{$namespace}' n'existe pas : {$path}");
        }

        $this->namespaces[$namespace] = $path;
    }

    public function render(string $tpl, array $args = [])
    {
        list(
            $template_type,
            $template_name,
            $template_variant,
            $namespace,
        ) = carlo_explode_id($tpl);

        if ($template_type !== 'menus') {
            $args = $this->complete($this->fields($tpl), $args);
        }

        $this->tplArgs[] = $args;

        // Les données sont dépilées même si le template lève une exception : sinon carlo_get()
        // lirait ensuite celles d'un rendu terminé.
        try {
            $file = $this->getFile(
                "template",
                "{$template_type}/{$template_name}",
                $template_variant,
                $namespace
            );

            echo "<!-- begin {$file} -->\n";
            include $file;
            echo "<!-- end {$file} -->\n";
        } finally {
            array_pop($this->tplArgs);
        }
    }

    /**
     * Champs d'un élément (sections/text, components/cta:secondary…), d'après sa structure.
     */
    public function fields(string $id): array
    {
        [$type, $name, $variant, $namespace] = carlo_explode_id($id);
        return $this->fields[$id] ??= Fields::of($this->structure($type ?: 'templates', $name, $variant, $namespace));
    }

    /**
     * Données d'un template à partir de leur source (attributs d'un bloc, exemple du catalogue,
     * données de démo). La mise en forme n'est pas idempotente (nl2br…) : load() est appelé une
     * seule fois, là où les données entrent ; render() ne fait que les compléter (cf. complete()).
     */
    public function load(string $id, array $raw = [], array $options = []): array
    {
        return $this->loadFields($this->fields($id), $raw, new LoadContext([], carlo_explode_id($id)[1], $options));
    }

    /**
     * Ajoute des données au template en cours (ou au niveau racine), pour carlo_get().
     */
    public function assign(array $data): void
    {
        $level = array_key_last($this->tplArgs);
        $this->tplArgs[$level] = array_merge($this->tplArgs[$level], $data);
    }

    /**
     * Seul parcours des champs. Les valeurs hors structure (arguments passés par un parent,
     * composant sans .yml) passent telles quelles ; un groupe prend l'_id de sa structure.
     */
    private function loadFields(array $fields, array $raw, LoadContext $ctx): array
    {
        $data = $raw;
        foreach ($fields as $key => $field) {
            $value = $raw[$key] ?? null;
            $data[$key] = match ($field['type']) {
                'group' => ['_id' => $field['id']] + $this->loadFields($field['fields'], is_array($value) ? $value : [], $ctx->child($key)),
                'repeater' => $this->loadRepeater($field, $value, $ctx->child($key)),
                default => $this->loadValue($field, $value, $ctx->child($key)),
            };
        }
        return $data;
    }

    /**
     * Valeur d'un champ simple ; les drivers y ajoutent leur mise en forme ou leur source.
     */
    protected function loadValue(array $field, mixed $value, LoadContext $ctx): mixed
    {
        return $value ?? $field['default'];
    }

    /**
     * Éléments d'un répéteur ; les drivers peuvent en ajouter (données de démo, aperçu).
     */
    protected function loadRepeater(array $field, mixed $value, LoadContext $ctx): array
    {
        $items = [];
        foreach (is_array($value) ? $value : [] as $i => $item) {
            $items[$i] = $this->loadFields($field['fields'], is_array($item) ? $item : [], $ctx->child($i));
        }
        return $items;
    }

    /**
     * Données sûres pour un template : chaque champ déclaré est présent (valeur par défaut de son
     * type) et chaque groupe a l'_id de sa structure. Sans mise en forme et idempotent : un
     * template peut rendre à nouveau un composant avec des données déjà complétées.
     */
    private function complete(array $fields, array $data): array
    {
        foreach ($fields as $key => $field) {
            $value = $data[$key] ?? $field['default'];
            $data[$key] = match ($field['type']) {
                'group' => ['_id' => $field['id']] + $this->complete($field['fields'], is_array($value) ? $value : []),
                'repeater' => array_map(fn($item) => $this->complete($field['fields'], is_array($item) ? $item : []), is_array($value) ? $value : []),
                default => $value,
            };
        }
        return $data;
    }

    public function structure(string $type, ?string $name = null, string $variant = "base", string $namespace = 'default')
    {
        $source = null; // fichier en cours de lecture, pour les messages d'erreur
        $no_tag = null;
        $no_tag = function ($definition) use (&$no_tag, &$source) {
            if (is_array($definition)) {
                return array_map($no_tag, $definition);
            }
            if ($definition instanceof TaggedValue && $definition->getTag() !== "load") {
                throw new Exception("Tag YAML inconnu « !{$definition->getTag()} » dans {$source} : seul !load est reconnu.");
            }
            if ($definition instanceof TaggedValue) {
                list($template_type, $template_name, $template_variant, $def_namespace) = carlo_explode_id(
                    $definition->getValue()
                );

                return $no_tag(
                    $this->structure($template_type, $template_name, $template_variant, $def_namespace)
                );
            }
            return $definition;
        };

        if (!isset($this->structure)) {
            $source = $this->projectStructure;
            $this->structure = Yaml::parseFile(
                $this->projectStructure,
                Yaml::PARSE_CUSTOM_TAGS
            );
            foreach ($this->structure as $k => $v) {
                $this->structure[$k] = $no_tag($v);
            }
        }

        if (!empty($name)) {
            $cache_key = "{$namespace}:{$type}:{$name}:{$variant}";

            if (!isset($this->loaded[$cache_key])) {
                $this->loaded[$cache_key] = true;

                try {
                    $file = $this->getFile("structure", "{$type}/{$name}", $variant, $namespace);
                    $source = $file;
                    $parsed = $no_tag(Yaml::parseFile($file, Yaml::PARSE_CUSTOM_TAGS));

                    // Stockage dans la structure selon le namespace
                    if ($namespace !== 'default') {
                        if (!isset($this->structure['@namespaces'])) {
                            $this->structure['@namespaces'] = [];
                        }
                        if (!isset($this->structure['@namespaces'][$namespace])) {
                            $this->structure['@namespaces'][$namespace] = [];
                        }
                        if (!isset($this->structure['@namespaces'][$namespace][$type])) {
                            $this->structure['@namespaces'][$namespace][$type] = [];
                        }
                        if (!isset($this->structure['@namespaces'][$namespace][$type][$name])) {
                            $this->structure['@namespaces'][$namespace][$type][$name] = [];
                        }

                        $this->structure['@namespaces'][$namespace][$type][$name][$variant] = $parsed;
                    } else {
                        if (!isset($this->structure[$type])) {
                            $this->structure[$type] = [];
                        }
                        if (!isset($this->structure[$type][$name])) {
                            $this->structure[$type][$name] = [];
                        }
                        $this->structure[$type][$name][$variant] = $parsed;
                    }

                } catch (FileNotFoundException $e) {
                    // structure file is not required
                    $label = str_replace('_', ' ', $variant ?: $name);
                    $struct = ["_label" => ucfirst($label)];

                    if ($namespace !== 'default') {
                        if (!isset($this->structure['@namespaces'][$namespace])) {
                            $this->structure['@namespaces'][$namespace] = [];
                        }
                        if (!isset($this->structure['@namespaces'][$namespace][$type])) {
                            $this->structure['@namespaces'][$namespace][$type] = [];
                        }
                        if (!isset($this->structure['@namespaces'][$namespace][$type][$name])) {
                            $this->structure['@namespaces'][$namespace][$type][$name] = [];
                        }
                        $this->structure['@namespaces'][$namespace][$type][$name][$variant] = $struct;
                    } else {
                        if (!isset($this->structure[$type])) {
                            $this->structure[$type] = [];
                        }
                        if (!isset($this->structure[$type][$name])) {
                            $this->structure[$type][$name] = [];
                        }
                        $this->structure[$type][$name][$variant] = $struct;
                    }
                }

                // Ajouter l'_id avec le namespace si présent
                $id = $namespace !== 'default'
                    ? "@{$namespace}:{$type}/{$name}:{$variant}"
                    : "{$type}/{$name}:{$variant}";

                if ($namespace !== 'default') {
                    $this->structure['@namespaces'][$namespace][$type][$name][$variant]["_id"] = $id;
                } else {
                    $this->structure[$type][$name][$variant]["_id"] = $id;
                }
            }
        }

        if (empty($name) && isset($this->structure[$type])) {
            return $this->structure[$type];
        }

        // Retourner la structure en fonction du namespace
        if ($namespace !== 'default') {
            return $this->structure['@namespaces'][$namespace][$type][$name][$variant] ?? null;
        }

        return $this->structure[$type][$name][$variant] ?? null;
    }

    public function register(string $file)
    {
        $this->projectStructure = $file;
    }

    public function get(?string $key = null)
    {
        $tpl_args = end($this->tplArgs);

        if ($key === null) {
            return $tpl_args;
        }

        $values = carlo_get_value($key, $tpl_args);

        if ($values === "") {
            $values = carlo_context($key);
        }

        return $values;
    }

    public function getFile(string $type, string $element, string $variant = 'base', string $namespace = 'default')
    {
        $variant = $variant ?: "base";

        // Vérifier que le namespace est enregistré
        if (!isset($this->namespaces[$namespace])) {
            throw new Exception("Le namespace '{$namespace}' n'est pas enregistré. Utilisez carlo_register_namespace() pour l'ajouter.");
        }

        // Utiliser la fonction getPaths
        $local_paths = $this->getPathsToTest($this->extension($type), $element, $variant, $namespace);


        // Ajouter namespace ou carlo aux chemins
        foreach ($local_paths as $path) {
            if (file_exists($this->namespaces[$namespace] . '/' . $path)){
                return $this->namespaces[$namespace] . '/' . $path;
            }
        }

        $namespace_info = $namespace !== 'default' ? " (namespace: @{$namespace})" : "";
        throw new FileNotFoundException(
            "Aucun fichier ne correspond : {$type} - {$element} - {$variant}{$namespace_info}\nCherché dans : " . implode(", ", $local_paths)
        );
    }

    /**
     * Extension des fichiers d'un élément selon leur rôle : structure, exemples du catalogue
     * (stories) ou template.
     */
    protected function extension(string $type): string
    {
        return ["structure" => "yml", "stories" => "stories.yml"][$type] ?? "php";
    }

    public function getPathsToTest(string $type, string $element, string $variant = 'base', string $namespace = 'default')
    {
        $paths = [];

        // Si on a un namespace, générer d'abord les chemins avec namespace
        if ($namespace !== 'default') {
            $prefix = "@{$namespace}/";

            if ($variant === 'base') {
                $paths[] = "{$prefix}{$element}.{$type}";
                $paths[] = "{$prefix}{$element}/base.{$type}";
            } else {
                $paths[] = "{$prefix}{$element}/{$variant}.{$type}";
                $paths[] = "{$prefix}{$element}/base.{$type}";
                $paths[] = "{$prefix}{$element}.{$type}";
            }
        }

        // Toujours générer les chemins sans namespace
        if ($variant === 'base') {
            $paths[] = "{$element}.{$type}";
            $paths[] = "{$element}/base.{$type}";
        } else {
            $paths[] = "{$element}/{$variant}.{$type}";
            $paths[] = "{$element}/base.{$type}";
            $paths[] = "{$element}.{$type}";
        }

        return $paths;
    }
}
