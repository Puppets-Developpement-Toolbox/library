


<!-- <pre>
  <?php var_dump(carlo_get()) ?>
</pre> -->

<?php

  $type = null;
  // $type = 'cards';

  $slides = carlo_get('cards');

?>



<?php if ($type !== 'cards'): ?>
  <div class="bg-primary text-white
            group/on-primary">
<?php endif ?>



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
      <ul class="flex flex-col gap-12
                laptop:grid laptop:grid-cols-3">
        <?php foreach ($slides as $slide): ?>
        <li>
          <?php carlo_render('components/card', $slide) ?>
        </li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>

      <aside class="flex justify-center
                    laptop:hidden">
        <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
      </aside>
      
    </section>



<?php if ($type !== 'cards'): ?>
  </div>
<?php endif ?>
