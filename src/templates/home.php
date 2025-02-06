<?php carlo_render('global/html_start') ?>
<?php carlo_context_add('current_page', 'home') ?>
<?php // carlo_render('global/header') ?>

<main class="page-content" id="main">
  <?php carlo_render('blocs/hero_banner') ?>
  <?php // carlo_render('blocs/slider') ?>
  <?php carlo_render('blocs/text_image') ?>
  <?php carlo_render('blocs/push') ?>
</main>

<?php //carlo_render('global/footer') ?>
<?php carlo_render('global/html_end') ?>