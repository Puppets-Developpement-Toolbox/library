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
            $template_name
        );

        $this->tplPaths[] = $template_name;
        $this->context[] = [];

        $defaultArgs =
            $template_type === "sections" ? $this->loadData($structure) : [];

        $this->tplArgs[] = array_merge($defaultArgs, $args);

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

    public function structure(string $type, string $name = null)
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
                list($template_type, $template_name) = carlo_explode_id(
                    $definition->getValue()
                );

                return $no_tag(
                    $this->structure($template_type, $template_name)
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

        if (!empty($name) && !isset($this->loaded[$type][$name])) {
            $this->loaded[$type][$name] = true;
            try {
                $file = carlo_get_file("structure", "{$type}/{$name}");
                $this->structure[$type][$name] = $no_tag(
                    Yaml::parseFile($file, Yaml::PARSE_CUSTOM_TAGS)
                );
                $this->structure[$type][$name]["_id"] = "{$type}/{$name}";
            } catch (Exception $e) {
            }
        }

        if (empty($name) && isset($this->structure[$type])) {
            return $this->structure[$type];
        }

        // certains templates peuvent ne pas avoir de structure associée
        return $this->structure[$type][$name] ?? null;
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
}
