<?php

declare(strict_types=1);

namespace carlo;


class Introspection
{
    private array $cache = [];

    public function __construct(
        private readonly DriverInterface $driver
    ) {}

    /**
     * Scan filesystem to discover available elements of a type
     */
    public function scanAvailableElements(string $type, string $namespace = 'default'): array
    {
        $elements = [];

        foreach ($this->variantsByElement($type, $namespace) as $elementName => $variants) {
            foreach ($variants as $variant) {
                $id = match ($variant) {
                    'base' => "{$type}/{$elementName}",
                    default => "{$type}/{$elementName}:{$variant}"
                };

                if ($namespace !== 'default') {
                    $id = "@{$namespace}:{$id}";
                }

                $elements[] = $id;
            }
        }

        return $elements;
    }

    /**
     * Get full metadata for an element (from its .yml, resolved by the driver)
     */
    public function getElementMetadata(string $id): ?array
    {
        if (isset($this->cache["meta:{$id}"])) {
            return $this->cache["meta:{$id}"];
        }

        $parts = $this->parseElementId($id);
        if (!$parts) {
            return null;
        }

        ['namespace' => $namespace, 'type' => $type, 'name' => $name, 'variant' => $variant] = $parts;

        if (!in_array($variant, $this->variantsByElement($type, $namespace)[$name] ?? [], true)) {
            return null;
        }

        // Le driver applique les surcharges (thème…) et résout les tags !load.
        $metadata = $this->driver->structure($type, $name, $variant, $namespace);

        if (!is_array($metadata)) {
            $metadata = [];
        }

        // Add computed fields
        $metadata['_id'] = $id;
        $metadata['_type'] = $type;
        $metadata['_name'] = $name;
        $metadata['_variant'] = $variant;
        $metadata['_namespace'] = $namespace;
        $metadata['_fields'] = $this->extractFields($metadata);

        $this->cache["meta:{$id}"] = $metadata;
        return $metadata;
    }

    /**
     * Get all variants for a specific element
     */
    public function getElementVariants(string $type, string $name, string $namespace = 'default'): array
    {
        return $this->variantsByElement($type, $namespace)[$name] ?? [];
    }

    /**
     * Get all registered namespaces
     */
    public function getRegisteredNamespaces(): array
    {
        return $this->driver->getNamespaces();
    }

    /**
     * Get comprehensive information about all available blocks
     */
    public function getAllElements(string $type, bool $includeMetadata = false): array
    {
        $result = [];
        $namespaces = $this->getRegisteredNamespaces();

        foreach ($namespaces as $namespace => $path) {
            $elements = $this->scanAvailableElements($type, $namespace);

            if ($includeMetadata) {
                foreach ($elements as $id) {
                    $result[$id] = $this->getElementMetadata($id);
                }
            } else {
                $result = [...$result, ...$elements];
            }
        }

        return $result;
    }

    // --- Private helper methods ---

    /**
     * Variantes de chaque élément d'un type, dans tous les dossiers de recherche du driver :
     * type/nom/variante.php ou .yml (une variante peut n'avoir que l'un des deux), et type/nom.php
     * (variante base). Mêmes emplacements que BaseDriver::getPathsToTest().
     */
    private function variantsByElement(string $type, string $namespace): array
    {
        $cacheKey = "scan:{$namespace}:{$type}";

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $variants = [];

        foreach ($this->driver->getSearchPaths($namespace) as $basePath) {
            $typePath = "{$basePath}/{$type}";

            // Pas de GLOB_BRACE : absent de certaines libc (Alpine).
            $files = [...(glob("{$typePath}/*/*.php") ?: []), ...(glob("{$typePath}/*/*.yml") ?: [])];
            foreach ($files as $file) {
                $variant = pathinfo($file, PATHINFO_FILENAME);
                // Écarte les fichiers annexes (base.stories.yml…).
                if (preg_match('/^[\w-]+$/', $variant)) {
                    $variants[basename(dirname($file))][$variant] = $variant;
                }
            }

            foreach (glob("{$typePath}/*.php") ?: [] as $file) {
                $variants[basename($file, '.php')]['base'] = 'base';
            }
        }

        ksort($variants);
        $variants = array_map(function (array $names) {
            // base d'abord, puis les autres variantes par ordre alphabétique.
            sort($names);
            return in_array('base', $names, true) ? ['base', ...array_diff($names, ['base'])] : $names;
        }, $variants);

        $this->cache[$cacheKey] = $variants;
        return $variants;
    }

    /**
     * Parse element ID into components
     * Format: [@namespace:]type/name[:variant]
     */
    private function parseElementId(string $id): ?array
    {
        $namespace = 'default';
        $remaining = $id;

        // Extract namespace
        if (str_starts_with($id, '@')) {
            if (!preg_match('/^@([^:]+):(.+)$/', $id, $matches)) {
                return null;
            }
            $namespace = $matches[1];
            $remaining = $matches[2];
        }

        // Extract variant
        $variant = 'base';
        if (str_contains($remaining, ':')) {
            [$remaining, $variant] = explode(':', $remaining, 2);
        }

        // Extract type and name
        if (!str_contains($remaining, '/')) {
            return null;
        }

        [$type, $name] = explode('/', $remaining, 2);

        return compact('namespace', 'type', 'name', 'variant');
    }


    /**
     * Extract field definitions from metadata
     */
    private function extractFields(array $metadata): array
    {
        $fields = [];

        foreach ($metadata as $key => $value) {
            // Skip metadata keys (starting with _)
            if (str_starts_with($key, '_')) {
                continue;
            }

            $fields[$key] = $this->normalizeFieldDefinition($key, $value);
        }

        return $fields;
    }

    /**
     * Normalize field definition to consistent format
     */
    private function normalizeFieldDefinition(string $key, mixed $value): array
    {
        // If simple type (string)
        if (is_string($value)) {
            return [
                'type' => $value,
                'label' => ucfirst($key),
                'required' => false
            ];
        }

        // If not array, return unknown type
        if (!is_array($value)) {
            return ['type' => 'unknown', 'label' => $key];
        }

        // Extract metadata from array
        $normalized = [
            // Même règle que les drivers : avec _id, un composant (groupe) ; sinon une liste de blocs.
            'type' => $value['_type'] ?? (isset($value['_id']) ? 'group' : 'container'),
            'label' => $value['_label'] ?? ucfirst($key),
            'required' => $value['_required'] ?? false
        ];

        // Optional metadata keys to extract
        $optionalKeys = [
            '_help' => 'help',
            '_multi' => 'multi',
            '_choices' => 'choices',
            '_min' => 'min',
            '_max' => 'max',
            '_times' => 'times',
            '_repeat' => 'repeat',
            '_items' => 'items',
            '_ref_type' => 'ref_type',
            '_taxo' => 'taxonomy',
            '_add_button' => 'add_button',
        ];

        foreach ($optionalKeys as $sourceKey => $targetKey) {
            if (isset($value[$sourceKey])) {
                $normalized[$targetKey] = $value[$sourceKey];
            }
        }

        return $normalized;
    }
}
