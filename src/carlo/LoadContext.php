<?php

namespace carlo;

/**
 * Contexte du chargement d'une valeur (cf. BaseDriver::load()) : chemin du champ (quote.author,
 * cards.0.title), élément chargé (sa clé de recherche dans les données de démo) et options du
 * chargement (ex. aperçu de l'éditeur), que seuls les drivers interprètent.
 */
final class LoadContext
{
    public function __construct(
        public readonly array $path = [],
        public readonly string $element = '',
        public readonly array $options = [],
    ) {
    }

    public function child(string|int $key): self
    {
        return new self([...$this->path, $key], $this->element, $this->options);
    }
}
