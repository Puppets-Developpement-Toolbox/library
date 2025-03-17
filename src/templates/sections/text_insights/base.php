


<?php

  $insights = carlo_get('insights');

?>

<div <?= !empty(carlo_get('ancre')) ? 'id="' . carlo_get('ancre') . '"' : '' ?> class="bg-primary text-white
            group/on-primary">
  <section class="[ section ]
                  flex flex-col gap-12 my-0 py-20
                  bg-primary
                  laptop:grid laptop:grid-cols-12 laptop:gap-0 laptop:py-30">



    <header class="text-white
                  laptop:col-span-5">
        <div class="flex flex-col gap-4
                    laptop:gap-8 laptop:sticky laptop:top-34">

            <figure class="[ img__cover ]
                        aspect-[100/43]">
            <?= carlo_img('image', '750x322') ?>
            </figure>

            <div class="flex flex-col gap-4 pb-6
                        border-b-1 border-b-white
                        laptop:pb-8">
            <h2 class="[ h2 ] text-white">
                <?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?>
            </h2>
            <p class="[ kicker-subtitle ] text-white">
                <?= carlo_get('subtitle') ?>
            </p>
        </div>
        <div class="[ texte ]">
            <?= carlo_get('text') ?>
        </div>
        <?php if (carlo_get("cta")) {
            carlo_render("components/cta", carlo_get("cta"));
        } ?>
    </header>


    <ul class="flex flex-col gap-6
              laptop:col-span-6 laptop:col-start-7">
    <?php foreach ($insights as $insight): ?>
      <li class="group/insight">
        <?= carlo_render('components/insight', $insight) ?>
      </li>
    <?php endforeach; ?>
    </ul>


  </section>
</div>
