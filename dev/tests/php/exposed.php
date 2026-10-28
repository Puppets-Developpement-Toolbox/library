<?php
/**
 * Test des données exposées (_expose, meta:, carlo_get_exposed) sur le site local.
 *
 * Usage : lando wp eval-file tests/php/exposed.php
 * Utilise le type `restaurant` et la section `horaires` du thème carlo-test. Les contenus et
 * les sections créés sont supprimés à la fin. Code de sortie 1 au moindre échec.
 */

$failures = [];
$check = function (string $label, bool $ok, mixed $detail = null) use (&$failures) {
    echo ($ok ? '✓ ' : '✗ ') . $label . (!$ok && $detail !== null ? ' : ' . json_encode($detail, JSON_UNESCAPED_UNICODE) : '') . "\n";
    if (!$ok) {
        $failures[] = $label;
    }
};
$throws = function (callable $fn): ?string {
    try {
        $fn();
    } catch (Throwable $e) {
        return $e->getMessage();
    }
    return null;
};

// Contenu d'un restaurant : une région dont la section horaires porte les attributs donnés.
$content = fn(array $attrs) => serialize_block([
    'blockName' => 'carlo/region',
    'attrs' => ['region' => 'content'],
    'innerBlocks' => [['blockName' => carlo_block_name('sections/horaires'), 'attrs' => $attrs, 'innerBlocks' => [], 'innerHTML' => '', 'innerContent' => []]],
    'innerHTML' => '',
    'innerContent' => [null],
]);

$original = carlo_driver();
$created = [];
$themeSections = get_stylesheet_directory() . '/templates/sections';

try {
    $full = wp_insert_post([
        'post_type' => 'restaurant',
        'post_status' => 'publish',
        'post_title' => 'TEST-exposed plein',
        'post_content' => $content(['hours' => [['day' => 'Lundi', 'time' => '9h-18h'], ['day' => 'Samedi', 'time' => '10h-13h']], 'note' => 'Fermé le dimanche']),
        'meta_input' => ['google_place_id' => 'abc123', 'accepts_reservations' => true],
    ]);
    $empty = wp_insert_post(['post_type' => 'restaurant', 'post_status' => 'publish', 'post_title' => 'TEST-exposed vide']);
    $page = wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'TEST-exposed page', 'meta_input' => ['google_place_id' => 'pas-un-restaurant']]);
    array_push($created, $full, $empty, $page);

    // Sections exposées
    $section = carlo_get_exposed('opening_hours', $full);
    $check('section entière', ($section['note'] ?? null) === 'Fermé le dimanche' && count($section['hours'] ?? []) === 2, $section);
    $check('champ répéteur', array_column(carlo_get_exposed('hours_list', $full), 'day') === ['Lundi', 'Samedi'], carlo_get_exposed('hours_list', $full));
    $check('champ simple', carlo_get_exposed('opening_note', $full) === 'Fermé le dimanche');
    $check('source donnée par WP_Post', carlo_get_exposed('opening_note', get_post($full)) === 'Fermé le dimanche');
    $check('section absente du contenu : valeurs par défaut', carlo_get_exposed('opening_hours', $empty) === ['hours' => [], 'note' => ''], carlo_get_exposed('opening_hours', $empty));
    $check('sans source ni contenu affiché : valeur par défaut', carlo_get_exposed('opening_note') === '');

    // Meta du type
    $check('meta texte', carlo_get_exposed('google_place_id', $full) === 'abc123');
    $check('meta booléen', carlo_get_exposed('accepts_reservations', $full) === true, carlo_get_exposed('accepts_reservations', $full));
    $check('meta absent : valeur par défaut', carlo_get_exposed('reservation_url', $full) === '', carlo_get_exposed('reservation_url', $full));
    $check('meta d\'un autre type : ignoré', carlo_get_exposed('google_place_id', $page) === '');

    // Enregistrement
    $registered = get_registered_meta_keys('post', 'restaurant');
    $check('meta enregistrés dans l\'API REST', count(array_filter(['google_place_id', 'reservation_url', 'accepts_reservations'], fn($k) => !empty($registered[$k]['show_in_rest']))) === 3, array_keys($registered));
    $check('type de contenu avec custom-fields', post_type_supports('restaurant', 'custom-fields'));
    update_post_meta($full, 'reservation_url', 'javascript:alert(1)');
    $check('l\'URL est nettoyée', !str_contains((string) get_post_meta($full, 'reservation_url', true), 'javascript'), get_post_meta($full, 'reservation_url', true));
    $check('meta interrogeable par WP_Query', (new WP_Query(['post_type' => 'restaurant', 'meta_key' => 'google_place_id', 'meta_value' => 'abc123', 'fields' => 'ids']))->posts === [$full]);
    $settings = apply_filters('block_editor_settings_all', [], new WP_Block_Editor_Context(['post' => get_post($full)]));
    $check('panneau décrit dans les réglages de l\'éditeur', ($settings['carloMeta']['postType'] ?? null) === 'restaurant'
        && array_keys((array) ($settings['carloMeta']['fields'] ?? [])) === ['google_place_id', 'reservation_url', 'accepts_reservations'], $settings['carloMeta'] ?? null);

    // Nom inconnu
    $message = $throws(fn() => carlo_get_exposed('inconnu', $full));
    $check('nom inconnu : exception explicite', $message !== null && str_contains($message, 'inconnu'), $message);

    // Règles vérifiées à la lecture de la structure (un nouveau driver reconstruit l'index)
    $temp = function (string $name, string $yml) use ($themeSections, &$created) {
        wp_mkdir_p("$themeSections/$name");
        file_put_contents("$themeSections/$name/base.yml", $yml);
        file_put_contents("$themeSections/$name/base.php", '');
        $created[] = "$themeSections/$name";
    };
    $fresh = function () {
        carlo_driver(new CarloWpDriver());
        carlo_register(get_stylesheet_directory() . '/structure.yml');
    };

    $temp('tmp_doublon', "_expose: opening_hours\ntitre: text_short\n");
    $fresh();
    $message = $throws(fn() => carlo_get_exposed('opening_hours', 0));
    $check('nom exposé en doublon : exception', $message !== null && str_contains($message, 'plusieurs fois'), $message);
    array_map('unlink', glob("$themeSections/tmp_doublon/*"));
    rmdir("$themeSections/tmp_doublon");

    $temp('tmp_repeteur', "liste:\n  _type: repeater\n  _repeat:\n    nom:\n      _type: text_short\n      _expose: tmp_nom\n");
    $fresh();
    $message = $throws(fn() => carlo_get_exposed('opening_hours', 0));
    $check('_expose dans un répéteur : exception', $message !== null && str_contains($message, 'répéteur'), $message);
    array_map('unlink', glob("$themeSections/tmp_repeteur/*"));
    rmdir("$themeSections/tmp_repeteur");

    // Une variante (horaires:nuit) peut porter les mêmes noms que sa section de base.
    $variant = "$themeSections/horaires/nuit";
    file_put_contents("$variant.yml", file_get_contents("$themeSections/horaires/base.yml"));
    file_put_contents("$variant.php", '');
    $created[] = "$variant.yml";
    $created[] = "$variant.php";
    $fresh();
    $message = $throws(fn() => carlo_get_exposed('opening_note', $full));
    $check('variantes d\'une même section : même nom accepté', $message === null && carlo_get_exposed('opening_note', $full) === 'Fermé le dimanche', $message);
} finally {
    foreach ($created as $item) {
        if (is_int($item)) {
            wp_delete_post($item, true);
        } elseif (is_file($item)) {
            unlink($item);
        } elseif (is_dir($item)) {
            array_map('unlink', glob("$item/*"));
            rmdir($item);
        }
    }
    carlo_driver($original);
}

echo $failures ? "\n" . count($failures) . " échec(s)\n" : "\nTout est vert.\n";
exit($failures ? 1 : 0);
