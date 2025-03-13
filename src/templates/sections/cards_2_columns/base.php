


<?php

  $cards = carlo_get('cards');

?>

<section class="[ section section__push ]
                flex flex-col gap-8
                group-[&]/super-section:bg-transparent
                laptop:grid laptop:grid-cols-12">
<?php if(carlo_get("title") || carlo_get('description')): ?>
    <header class="laptop:col-span-8 laptop:col-start-2">
      <h2 class="[ h2 ]
                  mb-6">
        <?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title")));?>
      </h2>
      <div class="laptop:flex laptop:justify-between">
        <div class="[ large ]
                    text-black
                    laptop:shrink-0 laptop:w-full">
          <?= carlo_get("description") ?>
        </div>
      </div>
    </header>
<?php endif; ?>

    <?php if (!empty($cards)): ?>
    <ul class="flex flex-col gap-8
              laptop:col-span-10 laptop:col-start-2 laptop:grid laptop:grid-cols-2">
      <?php foreach ($cards as $card):
        if(!empty($card['title'])): $card['border'] = true; ?>
      <li>
        <?php carlo_render('components/card', $card); ?>
      </li>
      <?php endif;
      endforeach; ?>
    </ul>
    <?php endif; ?>

  </section>
