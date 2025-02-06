


<!-- <pre>
  <?php var_dump(carlo_get()) ?>
</pre> -->

<?php

  $type = 'cards';

  $slides = carlo_get('cards');

?>

<section class="[ section section__push ]
                flex flex-col gap-8">
  
  <header>
    <div>
      <h2 class="[ h2 ]
                mb-6"><?= carlo_get('title') ?></h2>
      <?= carlo_get('description') ?>
    </div>
    <aside class="hidden laptop:block">
      <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
    </aside>
  </header>
  
  <?php if ($slides): ?>
  <ul class="flex flex-col gap-12">
    <?php foreach ($slides as $slide): ?>
    <li>
      <figure class="[ img__cover ] aspect-[8/5]">
        <?= $slide['image'] ?>
      </figure>
      <div class="p-8
                  border-1 border-t-0 border-black">
        <header class="flex flex-col gap-1 mb-6 pb-4
                      border-b-1 border-b-black">
          <h3 class="[ h3 ]"><?= $slide['title'] ?></h3>
          <span class="[ kicker-subtitle ]"><?= $slide['subtitle'] ?></span>
        </header>
        <?= $slide['content'] ?>
      </div>
    </li>
    <?php endforeach ?>
  </ul>
  <?php endif ?>

  <aside class="laptop:hidden">
    <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
  </aside>
  
</section>
