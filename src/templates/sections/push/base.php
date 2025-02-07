


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
    <li class="group/card">
      <figure class="[ img__cover ]
                    aspect-[8/5] overflow-clip">
        <div class="scale-[115%]
                    transition-transform duration-500
                    group-hover/card:scale-100">
          <?= $slide['image'] ?>
        </div>
      </figure>
      <div class="flex flex-col gap-6 p-8
                  border-1 border-t-0 border-black">
        <header class="flex flex-col gap-2
                      after:transition-transform after:duration-500
                      after:block after:origin-left after:w-full after:h-[1px] after:mt-4 after:bg-black
                      group-hover/card:after:scale-x-90">
          <h3 class="[ h3 ]"><?= $slide['title'] ?></h3>
          <span class="[ kicker-subtitle ]"><?= $slide['subtitle'] ?></span>
        </header>
        <div><?= $slide['content'] ?></div>
        <?php carlo_render('components/cta:tertiary', ['link' => $slide['cta']['link'], 'label' => $slide['cta']['label']]) ?>
      </div>
    </li>
    <?php endforeach ?>
  </ul>
  <?php endif ?>

  <aside class="laptop:hidden">
    <?php if (carlo_get('cta')) carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus sur nos méthodes']) ?>
  </aside>
  
</section>
