<?php carlo_render('global/html_start') ?>
<?php carlo_context_add('current_page', 'actus') ?>
<?php carlo_render('global/header') ?>
 
<main class="page-content" id="main">
  <?php carlo_render('blocs/intro') ?>
  <?php carlo_render('blocs/breadcrumb') ?>
  <?php carlo_render('blocs/actu_title') ?>
  <?php carlo_render('blocs/actu_detail') ?>
  <?php carlo_render('blocs/actus_last') ?>

</main>

<?php carlo_render('global/footer') ?>
<?php carlo_render('global/html_end') ?>
