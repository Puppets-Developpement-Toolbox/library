<?php

namespace carlo;

use Exception;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

abstract class BaseDriver implements DriverInterface
{
    protected $tplArgs = [];
    protected $tplPaths = [];
    protected $context = [];
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

        if($template_type !== 'menus') {
            $structure = $this->structure(
                $template_type ?: "templates",
                $template_name,
                $template_variant,
                $namespace
            );
        } else {
            $structure = null;
        }

        $this->tplPaths[] = $template_name;
        $this->context[] = [];

        if($structure){
            $args = $template_type === "sections" ? $this->loadData($structure, $args) : $args;
        }

        $this->tplArgs[] = $args;

        $file = $this->getFile(
            "template",
            "{$template_type}/{$template_name}",
            $template_variant,
            $namespace
        );


        echo "<!-- begin {$file} -->\n";
        include $file;
        echo "<!-- end {$file} -->\n";

        array_pop($this->tplArgs);
        array_pop($this->tplPaths);
        array_pop($this->context);
    }

    public function structure(string $type, ?string $name = null, string $variant = "base", string $namespace = 'default')
    {
        $no_tag = null;
        $no_tag = function ($definition) use (&$no_tag) {
            if (is_array($definition)) {
                return array_map($no_tag, $definition);
            }
            if (
                $definition instanceof TaggedValue &&
                $definition->getTag() === "load"
            ) {
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

    public function loadData(array $structure, array $args)
    {
        foreach($structure as $key => $substruct) {
            if(str_starts_with($key, '_')) continue;
            if(is_string($substruct)) continue;

            if (is_array($args[$key]) && isset($substruct['_type']) && $substruct['_type'] == "repeater" ) {
                foreach($args[$key] as $arg_key => $arg_value){
                    $args[$key][$arg_key] = $this->loadData($substruct['_repeat'], $arg_value);
                }
            } else {
                if(is_array($args[$key]) && !empty($substruct['_id'])){
                    $args[$key] = $this->loadData($substruct, $args[$key]);
                }
            }
        }

        if(!isset($args['_id']) && isset($structure['_id'])) {
            $args['_id'] = $structure['_id'];
        }

        return $args;
    }

    public function getFile(string $type, string $element, string $variant = 'base', string $namespace = 'default')
    {
        $variant = $variant ?: "base";

        // Vérifier que le namespace est enregistré
        if (!isset($this->namespaces[$namespace])) {
            throw new Exception("Le namespace '{$namespace}' n'est pas enregistré. Utilisez carlo_register_namespace() pour l'ajouter.");
        }

        $ext = $type === "structure" ? "yml" : "php";

        $paths = [];

        // Utiliser la fonction getPaths
        $local_paths = $this->getPathsToTest($ext, $element, $variant, $namespace);


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
