<?php

/**
 * Catalogue des composants dans WordPress (la logique est dans carlo\Stories) : public, donc jamais chargé en production.
 *
 * - /?carlo-stories : catalogue, avec &id=<élément>&story=<exemple> pour en afficher un ;
 * - /?carlo-stories=frame&id=<élément>&story=<exemple> : rendu seul d'un exemple (iframe du catalogue).
 */

if ((defined('WP_ENV') ? WP_ENV : wp_get_environment_type()) === 'production') {
    return;
}

/**
 * Driver des exemples : la mise en forme de WordPress, avec le contenu généré de carlo\Placeholders.
 */
class CarloStoriesDriver extends CarloWpDriver
{
    use carlo\Placeholders;

    protected function placeholderReferences(array $field): array
    {
        return get_posts([
            'post_type' => $field['post_types'],
            'numberposts' => min($field['max'] ?: 3, 3),
            'fields' => 'ids',
        ]);
    }
}

/**
 * URL du catalogue ou d'un rendu seul.
 */
function carlo_stories_url($args = [])
{
    return add_query_arg(array_map('rawurlencode', $args), home_url('/?carlo-stories'));
}

function carlo_stories_frame($element, $story)
{
    // Driver des exemples, avec les namespaces enregistrés par le thème.
    $driver = new CarloStoriesDriver();
    $driver->placeholders = !$element['stories'];
    foreach (carlo_driver()->getNamespaces() as $namespace => $path) {
        $driver->registerNamespace($namespace, $path);
    }
    $driver->register(get_stylesheet_directory() . '/structure.yml');
    carlo_driver($driver);

    show_admin_bar(false);
    include __DIR__ . '/templates/frame.php';
}

// Accès depuis l'admin : Apparence > Catalogue. Une URL complète en slug sert directement de lien.
add_action('admin_menu', function () {
    add_submenu_page('themes.php', 'Catalogue des composants', 'Catalogue', 'edit_theme_options', carlo_stories_url());
});

add_action('template_redirect', function () {
    if (!isset($_GET['carlo-stories'])) {
        return;
    }
    header('X-Robots-Tag: noindex, nofollow');

    // Seuls les éléments découverts peuvent être rendus : pas de template arbitraire depuis l'URL.
    $element = carlo\Stories::find(wp_unslash($_GET['id'] ?? ''));
    $story_key = wp_unslash($_GET['story'] ?? '');

    if ($_GET['carlo-stories'] === 'frame') {
        if (!$element) {
            status_header(404);
            exit('Élément inconnu.');
        }
        // Sans exemple demandé (ou inconnu) : le premier, comme dans le catalogue.
        carlo_stories_frame($element, $element['stories'][$story_key] ?? reset($element['stories']) ?: []);
    } else {
        include __DIR__ . '/templates/catalogue.php';
    }
    exit;
});
