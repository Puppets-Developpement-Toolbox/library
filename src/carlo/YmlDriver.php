<?php

namespace carlo;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class YmlDriver extends BaseDriver implements DriverInterface
{
    private array $data;

    public function __construct(string $file)
    {
        $this->data = Yaml::parseFile($file);
    }

    public function loadData(array $structure)
    {
        $fields = array_filter(
            array_keys($structure),
            fn($key) => substr($key, 0, 1) !== "_"
        );

        $structure_data = array_map(function ($fieldname) use ($structure) {
            $field = $structure[$fieldname];
            if (is_array($field) && !isset($field["_type"])) {
                return $this->loadData($field);
            }

            if (is_string($field)) {
                $type = $field;
            } elseif (is_array($field)) {
                $type = $field["_type"];
            }

            if ($type == "repeater") {
                return array_fill(
                    0,
                    $field["_times"] ?? $field["_min"] + 3,
                    $this->loadData($field["_repeat"])
                );
            }

            $try = $this->tplPaths;
            do {
                $prefix = implode("--", $try);
                if ($prefix) {
                    $prefix .= "--";
                }

                if (isset($this->data["{$prefix}{$fieldname}"])) {
                    return $this->data["{$prefix}{$fieldname}"];
                }
                if (isset($this->data["{$prefix}{$type}"])) {
                    return $this->data["{$prefix}{$type}"];
                }
            } while (array_shift($try));
            $context = $this->tplPaths;
            $context[] = $fieldname;
            $fieldpath = implode(" > ", $context);

            throw new InvalidArgumentException(
                "Impossible de trouver du contenu pour le champ {$fieldpath} de type {$type}"
            );
        }, array_combine($fields, $fields));

        if (isset($structure["_id"])) {
            $structure_data["_id"] = $structure["_id"];
        }

        return $structure_data;
    }

    public function img(
        string $key,
        string $default_size,
        array $dimensions,
        $mobile_key,
        array $mobile_dimensions,
        array $attrs
    ) {
        $dimensions = array_merge($dimensions, $mobile_dimensions);
        $sources = [];
        foreach ($dimensions as $source => $size) {
            list($w, $h) = explode("x", $size);
            $sources[] = <<<HTML
    <source srcset="https://picsum.photos/{$w}/{$h}" media="{$source}">
HTML;
        }
        $sources_html = implode("/n", $sources);

        $default_size = empty($default_size) ? "200x200" : $default_size;
        list($w, $h) = explode("x", $default_size);

        $attrs_html = implode(' ', array_map(fn($n, $v) {
        return "{$n}={$v}";
        }, $attrs);
        return <<<HTML
    <picture>
        {$sources_html}
        <img src="https://picsum.photos/{$w}/{$h}" {$attrs_html} />
    </picture>
HTML;
    }
}
