<?php carlo_render('global/html_start') ?>
<?php carlo_context_add('current_page', 'who') ?>
<?php carlo_render('global/header') ?>

<main class="page-content" id="main">
  <?php carlo_render('blocs/intro') ?>
  <?php carlo_render('blocs/breadcrumb') ?>
  <?php carlo_render('blocs/title_description') ?>
  <?php carlo_render('blocs/columns_three_picto') ?>
  <?php carlo_render('blocs/slider_pillar') ?>
  <?php carlo_render('blocs/text_image') ?>
  <?php carlo_render('blocs/key_figures') ?>
  <?php carlo_render('blocs/text_bullet') ?>
  <?php carlo_render('blocs/workflow') ?>
  <?php carlo_render('blocs/image_full_width') ?>
</main>

<?php carlo_render('global/footer') ?>
<?php carlo_render('global/html_end') ?>
