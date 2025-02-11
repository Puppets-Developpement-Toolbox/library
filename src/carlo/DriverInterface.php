<?php

namespace carlo;

interface DriverInterface
{
    public function render(string $tpl, array $args = []);

    public function loadData(array $structure);

    public function structure(string $type, string $name = null);

    public function register(string $file);

    public function get(string $key = null);
}
