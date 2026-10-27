<?php
// Démo autonome des templates : seulement depuis un clone du dépôt, après `composer install`.
// Installé comme plugin, le paquet n'a pas son propre vendor/ : la page n'existe pas.
if (!is_file(__DIR__ . "/../vendor/autoload.php")) {
    http_response_code(404);
    exit;
}
include_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\ErrorHandler\Debug;
use carlo\YmlDriver;

Debug::enable();

// load data
$driver = new YmlDriver(__DIR__ . "/data.yml");
carlo_driver($driver);

// load demo structure
carlo_register(__DIR__ . "/structure.yml");

$templates = carlo_structure("templates");
$template = $_GET["template"] ?? null;

carlo_render("global/html_start");

if (!empty($template) && isset($templates[$template])) {
    ob_start();
    try {
        // Données de démo chargées une fois par section (cf. carlo_load()).
        foreach (array_column($templates[$template], "_id") as $id) {
            carlo_render($id, carlo_load($id));
        }
    } catch (Throwable $e) {
        ob_end_clean();
        throw $e;
    }
    ob_end_flush();
} else {
     ?>
    <section>
        <h1 class="h1">Templates&nbsp;:</h1>
        <ul>
            <?php foreach ($templates as $key => $conf): ?>
                <li><a href="index.php?template=<?= $key ?>"><?= $key ?></a>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
}

carlo_render("global/html_end");

