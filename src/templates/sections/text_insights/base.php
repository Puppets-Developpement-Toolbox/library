


<?php
  
  $insights = carlo_get('insights');

?>

<div class="bg-primary text-white
            group/on-primary">
  <section class="[ section ]
                  flex flex-col gap-12 py-20
                  bg-primary
                  laptop:grid laptop:grid-cols-12 laptop:gap-0 laptop:py-30">
    

                  
    <header class="text-white
                  laptop:col-span-5">
      <div class="laptop:sticky laptop:top-6">
          
        <figure class="[ img__cover ]
                      aspect-[100/43]">
          <?= carlo_get('image') ?>
        </figure>

        <div class="flex flex-col gap-4 my-6 pb-6
                    border-b-1 border-b-white">
          <h2 class="[ h2 ] text-white">
            <?= carlo_get('title') ?>
          </h2>
          <p class="[ kicker-subtitle ] text-white">
            <?= carlo_get('subtitle') ?>
          </p>
        </div>
      
      <?= carlo_get('text') ?>
      </div>
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
