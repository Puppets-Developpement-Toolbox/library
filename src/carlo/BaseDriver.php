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

    public function render(string $tpl, array $args = [])
    {
        list(
            $template_type,
            $template_name,
            $template_variant,
        ) = carlo_explode_id($tpl);

        $structure = $this->structure(
            $template_type ?: "templates",
            $template_name,
            $template_variant
        );

        $this->tplPaths[] = $template_name;
        $this->context[] = [];

        if($structure){
            $args = $template_type === "sections" ? $this->loadData($structure, $args) : $args;
        }

        $this->tplArgs[] = $args;

        $file = carlo_get_file(
            "template",
            "{$template_type}/{$template_name}",
            $template_variant
        );

        echo "<!-- begin {$file} -->\n";
        include $file;
        echo "<!-- end {$file} -->\n";
        array_pop($this->tplArgs);
        array_pop($this->tplPaths);
        array_pop($this->context);
    }

    public function structure(string $type, string $name = null, string $variant = "base")
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
                list($template_type, $template_name, $template_variant) = carlo_explode_id(
                    $definition->getValue()
                );

                return $no_tag(
                    $this->structure($template_type, $template_name, $template_variant)
                );
            }
            return $definition;
        };

        if (!isset($this->structure)) {
            $this->structure = Yaml::parseFile(
                $this->projectStructure,
                Yaml::PARSE_CUSTOM_TAGS
            );
            // loop over structure to load carlo @load tag
            foreach ($this->structure as $k => $v) {
                $this->structure[$k] = $no_tag($v);
            }
        }

        if (!empty($name) && !isset($this->loaded[$type][$name][$variant])) {
            $this->loaded[$type][$name][$variant] = true;
            try {
                $file = carlo_get_file(
                    "structure",
                    "{$type}/{$name}",
                    $variant
                );

                $this->structure[$type][$name][$variant] = $no_tag(
                    Yaml::parseFile($file, Yaml::PARSE_CUSTOM_TAGS)
                );
                $this->structure[$type][$name][$variant]["_id"] = "{$type}/{$name}:{$variant}";
            } catch (Exception $e) {
            }
        }

        if (empty($name) && isset($this->structure[$type])) {
            return $this->structure[$type];
        }

        // certains templates peuvent ne pas avoir de structure associée
        return $this->structure[$type][$name][$variant] ?? null;
    }

    public function register(string $file)
    {
        $this->projectStructure = $file;
    }

    public function get(string $key = null)
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

    public function loadData(array $structure, array $args) {

        foreach($structure as $key => $substruct) {
            if(str_starts_with($key, '_')) continue;
            if(is_string($substruct)) continue;


            if ( isset($substruct['_type']) && $substruct['_type'] == "repeater" ) {
                foreach($args[$key] as $arg_key => $arg_value){
                    $args[$key][$arg_key] = $this->loadData($substruct['_repeat'], $arg_value);
                }
            } else {
                $args[$key] = $this->loadData($substruct, $args);
            }

        }



        if(!isset($args['_id']) && isset($structure['_id'])) $args['_id'] = $structure['_id'];

        return $args;
    }

    public function getFile(string $type, string $element, string $variant = 'base'){
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

        throw new FileNotFoundException(
            "Aucun fichier ne correspond à ce que l'on cherche : {$type} - {$element} - {$variant}"
        );
    }
}
