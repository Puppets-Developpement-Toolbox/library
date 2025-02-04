

<?php
  include_once __DIR__ . "/carlo.php";
  use Symfony\Component\Yaml\Yaml;
  $structure = Yaml::parseFile(__DIR__ . "/structure.yml");
  $template = $_GET["template"] ?? null;

  if (!empty($template) && isset($structure["templates"][$template])) {
      echo carlo_render($template);
  } else {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <!--
  replace XXX_title_XXX by final page title
  replace XXX_description_XXX by final description text
  replace XXX_url_XXX by final production URL
  replace XXX_thumb_XXX by final production URL of the thumb image for social networks
  -->
  <meta charset="utf-8">
  <title>XXX_title_XXX</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="description" content="XXX_description_XXX">
  <meta property="og:title" content="XXX_title_XXX">
  <meta property="og:description" content="XXX_description_XXX">
  <meta property="og:image" content="XXX_thumb_XXX">
  <meta property="og:site_name" content="XXX_siteName_XXX">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">
  <meta property="fb:app_id" content="XXX_fb_id_XXX">
  <meta property="og:url" content="XXX_url_XXX">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="XXX_@creator_XXX">
  <meta name="twitter:dnt" content="on">

  <meta name="format-detection" content="telephone=no">

  <link rel="canonical" href="XXX_url_XXX">
  <script type="module" src="https://front.library.lndo.site/@vite/client"></script>
  <link href="https://front.library.lndo.site/src/css/global.css" rel="stylesheet">
  <script src="https://front.library.lndo.site/src/js/main.js"></script>
</head>



<body>

  <section>
    <header></header>
    <h1 class="h1">Templates&nbsp;:</h1>
    <ul>
      <?php foreach ($structure["templates"] as $key => $conf): ?>
      <li><a href="index.php?template=<?= $key ?>"><?= $key ?></a>
      <?php endforeach; ?>
    </ul>
  </section>

</body>
</html>
<?php
}
