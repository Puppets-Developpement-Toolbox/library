<?php

namespace carlo;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class YmlDriver extends BaseDriver implements DriverInterface
{
    private array $data;

    public function __construct(string $file)
    {
        parent::__construct();
        $this->data = Yaml::parseFile($file);
    }

    // Données de démo : une valeur absente est cherchée dans le fichier de données.
    protected function loadValue(array $field, mixed $value, LoadContext $ctx): mixed
    {
        return parent::loadValue($field, $value ?? $this->mock($field, $ctx), $ctx);
    }

    // Répéteur sans valeur : (_min ou 2) + 3 éléments, ou _times.
    protected function loadRepeater(array $field, mixed $value, LoadContext $ctx): array
    {
        $value ??= array_fill(0, min($field['max'] ?? PHP_INT_MAX, ($field['min'] ?: 2) + 3), []);
        return parent::loadRepeater($field, $value, $ctx);
    }

    /**
     * Valeur de démo d'un champ : <élément>--<champ>, <élément>--<type>, puis <champ> et <type>.
     */
    private function mock(array $field, LoadContext $ctx): mixed
    {
        $key = $ctx->path[array_key_last($ctx->path)];
        foreach (["{$ctx->element}--", ''] as $prefix) {
            foreach (["{$prefix}{$key}", "{$prefix}{$field['type']}"] as $candidate) {
                if (isset($this->data[$candidate])) {
                    return $this->data[$candidate];
                }
            }
        }

        $fieldpath = implode(" > ", [$ctx->element, ...$ctx->path]);
        throw new InvalidArgumentException(
            "Impossible de trouver du contenu pour le champ {$fieldpath} de type {$field['type']}"
        );
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

        $attrs_html = implode(
            " ",
            array_map(fn($n, $v) => "{$n}={$v}", array_keys($attrs), $attrs)
        );
        return <<<HTML
    <picture>
        {$sources_html}
        <img src="https://picsum.photos/{$w}/{$h}" {$attrs_html} />
    </picture>
HTML;
    }
}
