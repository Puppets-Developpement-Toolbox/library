


<?php

  $cards = carlo_get('cards');

?>

<section class="[ section section__push ]
                flex flex-col gap-8
                laptop:grid laptop:grid-cols-12">

    <header class="laptop:col-span-10 laptop:col-start-2">
      <h2 class="[ h2 ]
                  mb-6">
        <?= carlo_get("title") ?>
      </h2>
      <div class="laptop:flex laptop:justify-between">
        <div class="laptop:shrink-0 laptop:w-2/3">
          <?= carlo_get("description") ?>
        </div>
      </div>
    </header>

    <?php if ($cards): ?>
    <ul class="flex flex-col gap-8
              laptop:col-span-10 laptop:col-start-2 laptop:grid laptop:grid-cols-2">
      <?php foreach ($cards as $card): ?>
      <li>
        <?php carlo_render('components/card', $card); ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

  </section>