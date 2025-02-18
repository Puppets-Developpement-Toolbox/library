<?php

namespace carlo;

interface DriverInterface
{
    public function render(string $tpl, array $args = []);

    public function loadData(array $structure, array $args);

    public function structure(string $type, string $name = null);

    public function register(string $file);

    public function get(string $key = null);

    public function img(
        string $key,
        string $default_size,
        array $source_sizes,
        $mobile_key, // string|null
        array $mobile_source_sizes,
        array $attrs
    );
}
