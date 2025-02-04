


<?php

  // $bgprimary = true;
  
  $padding = (isset($bgprimary) && $bgprimary) ? 'px-8 py-10 laptop:pl-0' : 'pt-12 laptop:p-0';
  $ratio = (isset($bgprimary) && $bgprimary) ? 'aspect-square' : 'aspect-[var(--ratio-img-mobile)]';
  $bg = (isset($bgprimary) && $bgprimary) ? 'bg-primary' : '';
  $text = (isset($bgprimary) && $bgprimary) ? 'text-white' : '';

  // Inversion distribution image/texte
  // $revert = true;

?>

<section class="[ section ]">
  <div class="laptop:flex <?php if ($revert): ?> flex-row-reverse <?php endif ?> laptop:justify-between
              <?= $bg ?>
              group/section">

    <div class="laptop:shrink-0 laptop:flex laptop:items-center laptop:w-1/2">
      <figure class="[ img__cover ]
                    <?= $ratio ?>">
        <?= carlo_get('image') ?>
      </figure>
    </div>
    
    <section class="laptop:w-1/2 laptop:flex laptop:items-center
                    <?= $text ?>
                    <?= $padding ?>">
      <div class="laptop:w-5/6 laptop:max-w-5/6 <?php if (!$revert): ?> laptop:ml-auto <?php endif ?>">
        <header class="mb-6">
          <?= carlo_get('surtitle') ?>
          <h2 class="[ h2 ]
                    <?= $text ?>"><?= carlo_get('title') ?></h2>
        </header>
        <?= carlo_get('description') ?>
        <?php if (carlo_get('cta_push')) carlo_render('components/cta_push', ['link' => '#', 'label' => 'En savoir plus']) ?>
      </div>
    </section>
  
  </div>
</section>
