<?php

namespace carlo;

interface DriverInterface
{
    public function render(string $tpl, array $args = []);

    public function load(string $id, array $raw = [], array $options = []): array;

    public function assign(array $data): void;

    /**
     * Donnée exposée sous un nom (_expose d'une section ou d'un champ, clé meta: d'un type), formatée
     * comme celle d'un template. $source désigne le contenu où la lire (selon le driver).
     */
    public function getExposed(string $name, mixed $source = null): mixed;

    public function structure(
        string $type,
        ?string $name = null,
        string $variant = "base",
        string $namespace = 'default'
    );

    public function register(string $file);

    public function registerNamespace(string $namespace, string $path);

    public function getFile(
        string $type,
        string $element,
        string $variant = 'base',
        string $namespace = 'default'
    );

    public function get(?string $key = null);

    /**
     * Dossiers où chercher les templates d'un namespace, du plus prioritaire au moins prioritaire.
     */
    public function getSearchPaths(string $namespace = 'default'): array;

    public function getNamespaces(): array;

    public function introspect(): Introspection;

    public function img(
        string $key,
        string $default_size,
        array $source_sizes,
        $mobile_key,
        array $mobile_source_sizes,
        array $attrs
    );
}
