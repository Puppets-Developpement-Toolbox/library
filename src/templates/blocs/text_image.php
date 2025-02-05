


<?php

  // Inversion distribution image/texte
  $revert = false;

  // Blocs carrés
  $bgprimary = true;

  // Setup
  $header = ($bgprimary) ? 'pb-6 border-b-[1px] border-b-white' : '';
  $text = ($bgprimary) ? 'laptop:w-2/3 laptop:max-w-2/3 laptop:mx-auto' : 'laptop:w-5/6 laptop:max-w-5/6';
  $padding = ($bgprimary) ? 'px-8 py-10 laptop:px-0 laptop:py-14' : 'pt-12 laptop:p-0';
  $ratio = ($bgprimary) ? 'aspect-square' : 'aspect-[var(--ratio-img-mobile)]';
  $bg = ($bgprimary) ? 'bg-primary' : '';
  $text = ($bgprimary) ? 'text-white' : '';

?>

<section class="[ section ]">
  <div class="group/section
              laptop:grid laptop:grid-cols-12
              <?php if ($bgprimary): ?> laptop:aspect-[2/1] laptop:overflow-hidden <?php endif ?>
              <?= $bg ?>">

    <div class="laptop:col-span-6
                <?php if (!$revert): ?>
                  laptop:col-start-1 laptop:order-1
                <?php else: ?>
                  laptop:col-start-7 laptop:order-2
                <?php endif ?>">
      <figure class="[ img__cover ]
                    <?= $ratio ?>">
        <?= carlo_get('image') ?>
      </figure>
    </div>
    
    <section class="laptop:col-span-6 laptop:flex laptop:gap-5
                    <?php if ($bgprimary): ?>
                      laptop:overflow-y-auto
                      laptop:items-start
                    <?php else: ?>
                      laptop:items-center
                    <?php endif ?>
                    <?php if (!$revert): ?>
                      laptop:col-start-8
                      <?php if ($bgprimary): ?> laptop:col-end-12 <?php endif ?>
                      laptop:order-2
                    <?php else: ?>
                      <?php if ($bgprimary): ?>
                        laptop:col-start-2 laptop:col-end-6
                      <?php else: ?>
                        laptop:order-1 laptop:col-start-1 laptop:col-end-6
                      <?php endif ?>
                    <?php endif ?>
                    <?= $padding ?>
                    <?= $text ?>">
      <div class="flex flex-col gap-6 laptop:gap-8
                  <?= $text ?>">
        <header class="<?= $header ?>">
          <?= carlo_get('surtitle') ?>
          <h2 class="[ h2 ]
                    <?= $text ?>">
            <?= carlo_get('title') ?>
          </h2>
        </header>
        <div><?= carlo_get('description') ?></div>
        <?php if (carlo_get('cta_push')) carlo_render('components/cta_push', ['link' => '#', 'label' => 'En savoir plus']) ?>
      </div>
    </section>
  
  </div>
</section>
