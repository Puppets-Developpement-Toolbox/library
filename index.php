

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
  <svg xmlns="http://www.w3.org/2000/svg" style="display:none;">
  <g id="svg-logo">
    <path fill="#6ebfad" d="M9.58 22.21h11.4v3.16H9.58zM23.93.02h3.62v25.35h-3.62zM35.77 8.51v16.86h-3.62V.03h2.71L41.2 15.7V.03h3.63v25.34h-2.58L35.77 8.51zM52.8 8.51v16.86h-3.62V.03h2.71l6.34 15.67V.03h3.62v25.34h-2.58L52.8 8.51zM79.72 8.03v9.19c0 5.09-1.84 8.4-6.99 8.4s-6.99-3.24-6.99-8.4V8.03c0-5.16 1.8-8.26 6.99-8.26s6.99 3.17 6.99 8.26m-10.36-.9v11c0 2.72.65 4.53 3.37 4.53s3.37-1.81 3.37-4.53v-11c0-2.72-.65-4.38-3.37-4.38s-3.37 1.67-3.37 4.38M92.72.03h3.62l-5.43 25.34h-3.62L81.86.03h3.62l2.67 13.57.94 6.34.96-6.34L92.72.03zM99.19 25.37h-3.62L101 .02h3.62l5.43 25.35h-3.62l-1.36-6.34h-4.53l-1.36 6.34Zm3.62-19.92L101 16.31h3.62l-1.81-10.86ZM121.76 3.25h-4.52v22.12h-3.62V3.25h-4.53V.03h12.67v3.22zM124.42.02h3.62v25.35h-3.62zM146.16 8.03v9.19c0 5.09-1.84 8.4-6.99 8.4s-6.99-3.24-6.99-8.4V8.03c0-5.16 1.79-8.26 6.99-8.26s6.99 3.17 6.99 8.26m-10.36-.9v11c0 2.72.65 4.53 3.37 4.53s3.37-1.81 3.37-4.53v-11c0-2.72-.65-4.38-3.37-4.38s-3.37 1.67-3.37 4.38M153.67 8.51v16.86h-3.62V.03h2.72l6.34 15.67V.03h3.62v25.34h-2.58l-6.48-16.86z"/><path fill="#ea6c50" d="M69.19 33.55v8.15h4.53v2.71h-4.53v11.77h-3.62V30.83h9.96v2.72h-6.34zM77.67 56.18h-3.62l5.43-25.35h3.62l5.43 25.35h-3.62l-1.36-6.34h-4.52l-1.36 6.34Zm3.62-19.92-1.81 10.86h3.62l-1.81-10.86ZM97.35 56.43c-5.45 0-6.96-3.87-6.96-9.32v-7.23c0-5.44 1.51-9.31 6.96-9.31 5.1 0 6.74 3.31 6.74 8.4h-3.62c0-3.08-.1-5.43-3.12-5.43s-3.34 2.35-3.34 5.43v9.05c0 2.77.58 5.43 3.34 5.43s3.12-2.66 3.12-5.43v-.9h3.62v.9c0 5.04-1.67 8.4-6.74 8.4M118.03 34.06h-4.52v22.12h-3.62V34.06h-4.53v-3.23h12.67v3.23zM133.02 38.84v9.19c0 5.09-1.84 8.4-6.99 8.4s-6.99-3.24-6.99-8.4v-9.19c0-5.16 1.8-8.26 6.99-8.26s6.99 3.17 6.99 8.26m-10.35-.9v11c0 2.72.65 4.53 3.36 4.53s3.37-1.81 3.37-4.53v-11c0-2.71-.65-4.38-3.37-4.38s-3.36 1.67-3.36 4.38M146.41 43.51l4.07 12.67h-3.59l-3.65-11.77h-2.72v11.77h-3.62V30.83h4.53c5.16 0 8.14 1.75 8.14 6.79 0 2.99-1.06 5.12-3.17 5.89m-4.98-9.96h-.91v8.15h.91c2.92 0 4.52-1.12 4.52-4.07s-1.6-4.07-4.52-4.07M155.52 48.03l-5.42-17.2h3.62l3.62 12.68 3.62-12.68h3.62l-5.43 17.2v8.15h-3.63v-8.15z"/>
  </g>
</svg>
  <div style="width:30rem; margin-bottom:4rem;">
    <svg viewBox="0 0 166.81 56.47"><use xlink:href="#svg-logo"></use></svg>
  </div>
  <h1>Templates&nbsp;:</h1>
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


?>
