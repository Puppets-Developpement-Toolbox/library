<?php carlo_render('global/html_start') ?>
<?php carlo_context_add('current_page', 'contact') ?>
<?php carlo_render('global/header') ?>
 
<main class="page-content" id="main">
  <?php carlo_render('blocs/intro') ?>
  <?php carlo_render('blocs/breadcrumb') ?>
  <?php carlo_render('blocs/contact_form') ?>
  <?php carlo_render('blocs/contact_map') ?>

</main>

<?php carlo_render('global/footer') ?>
<?php carlo_render('global/html_end') ?>
