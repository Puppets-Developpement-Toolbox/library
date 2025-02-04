


<?php
  // $revert = true;// Inversion positions image/texte
?>

<section class="[ section ]">
  <div class="laptop:flex <?php if ($revert): ?> flex-row-reverse <?php endif ?> laptop:justify-between">

    <div class="mb-12
                laptop:shrink-0 laptop:flex laptop:items-center laptop:w-1/2 laptop:mb-0">
      <figure class="[ img__cover ]
                    aspect-[var(--ratio-img-mobile)]">
        <?= carlo_get('image') ?>
      </figure>
    </div>
    
    <section class="laptop:w-1/2 laptop:flex laptop:items-center">
      <div class="laptop:w-5/6 laptop:max-w-5/6 <?php if (!$revert): ?> laptop:ml-auto <?php endif ?>">
        <header class="mb-6">
          <?= carlo_get('surtitle') ?>
          <h2 class="[ h2 ]">
            <?= carlo_get('title') ?>
          </h2>
        </header>
        <?= carlo_get('description') ?>
        <?php if (carlo_get('cta_push')) carlo_render('components/cta_push', ['link' => '#', 'label' => 'En savoir plus']) ?>
      </div>
    </section>
  
  </div>
</section>
