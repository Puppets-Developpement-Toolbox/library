


<section class="[ section ]">
  <figure class="[ img__cover ]
                aspect-[var(--ratio-img-mobile)] mb-12">
    <?= carlo_get('image') ?>
  </figure>
  <header class="mb-6">
    <?= carlo_get('surtitle') ?>
    <h2 class="[ h2 ]">
      <?= carlo_get('title') ?>
    </h2>
  </header>
  <?= carlo_get('description') ?>

  <?php if (carlo_get('cta_push')) carlo_render('components/cta_push', ['link' => '#', 'label' => 'En savoir plus']) ?>
</section>
