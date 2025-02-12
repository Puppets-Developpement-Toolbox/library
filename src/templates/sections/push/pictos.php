


<?php

  dump(carlo_get());
  $slides = carlo_get('cards');

?>



<div class="bg-primary text-white
            group/on-primary">



  <section class="[ section section__push ]
                  flex flex-col gap-8
                  group-[&]/on-primary:py-16 group-[&]/on-primary:bg-transparent group-[&]/on-primary:laptop:py-30">
    
    <header>
      <h2 class="[ h2 ]
                  mb-6
                  group-[&]/on-primary:text-white
                  laptop:col-span-12"><?= carlo_get('title') ?></h2>
      <div class="laptop:flex laptop:justify-between">
        <div class="laptop:shrink-0 laptop:w-2/3">
          <?= carlo_get('description') ?>
        </div>
        <aside class="hidden
                      laptop:block">
          <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
        </aside>
      </div>
    </header>
    
    <?php if ($slides): ?>
      <?php $cols = (count($slides) === 2 || count($slides) === 4) ? 'laptop:grid-cols-2' : 'laptop:grid-cols-3'; ?>
    <ul class="flex flex-col gap-6
              laptop:grid <?= $cols ?>">
      <?php foreach ($slides as $slide): ?>
      <li>
        <?php carlo_render('components/card:picto', $slide) ?>
      </li>
      <?php endforeach ?>
    </ul>
    <?php endif ?>

    <aside class="flex justify-center
                  laptop:hidden">
      <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
    </aside>
    
  </section>



</div>
