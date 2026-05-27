<?php

declare(strict_types=1);

namespace carlo;

use Symfony\Component\Yaml\Yaml;

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
        $cacheKey = "scan:{$namespace}:{$type}";

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $basePath = $this->getNamespacePath($namespace);

        if (!$basePath || !is_dir($basePath)) {
            return [];
        }

        $typePath = "{$basePath}/{$type}";

        if (!is_dir($typePath)) {
            return [];
        }

        $elements = [];
        $dirs = glob($typePath . '/*', GLOB_ONLYDIR) ?: [];

        foreach ($dirs as $dir) {
            $elementName = basename($dir);
            $ymlFiles = glob("{$dir}/*.yml") ?: [];

            foreach ($ymlFiles as $ymlFile) {
                $variant = basename($ymlFile, '.yml');

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

        $this->cache[$cacheKey] = $elements;
        return $elements;
    }

    /**
     * Get full metadata for an element (from .yml file)
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

        $basePath = $this->getNamespacePath($namespace);
        if (!$basePath) {
            return null;
        }

        $ymlPath = "{$basePath}/{$type}/{$name}/{$variant}.yml";

        if (!file_exists($ymlPath)) {
            return null;
        }

        try {
            $metadata = Yaml::parseFile($ymlPath);

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

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all variants for a specific element
     */
    public function getElementVariants(string $type, string $name, string $namespace = 'default'): array
    {
        $basePath = $this->getNamespacePath($namespace);
        if (!$basePath) {
            return [];
        }

        $elementPath = "{$basePath}/{$type}/{$name}";

        if (!is_dir($elementPath)) {
            return [];
        }

        $ymlFiles = glob("{$elementPath}/*.yml") ?: [];

        return array_map(
            fn(string $file) => basename($file, '.yml'),
            $ymlFiles
        );
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
     * Get namespace path
     */
    private function getNamespacePath(string $namespace): ?string
    {
        $namespaces = $this->getRegisteredNamespaces();
        return $namespaces[$namespace] ?? null;
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
            'type' => $value['_type'] ?? 'text',
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
