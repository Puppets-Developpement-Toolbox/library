


<?php

  $bg_primary = carlo_get("bg_primary");
  $gap_class = carlo_get("gap_class");
  $slides = carlo_get("slides");
  $slide_template = carlo_get("slide_template");

?>


<?php if ($bg_primary): ?>
<div class="bg-primary text-white
            group/on-primary">
<?php endif; ?>


  <section class="[ section section__push ]
                  flex flex-col gap-8
                  group-[&]/on-primary:py-16 group-[&]/on-primary:bg-transparent group-[&]/on-primary:laptop:py-30">
<?php if(!empty(carlo_get("title")) || !empty(carlo_get("description")) || (!empty(carlo_get('cta')['link']) && !empty(carlo_get('cta')['label']))): ?>
    <header class="flex flex-col gap-6">
      <?php if(!empty(carlo_get("title"))): ?>
      <h2 class="[ h2 ]
                  group-[&]/on-primary:text-white
                  laptop:col-span-12">
        <?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?>
      </h2>
      <?php endif; ?>

      <?php if(!empty(carlo_get("cta")) || !empty(carlo_get("description"))): ?>
      <div class="laptop:flex laptop:justify-between">
        <div class="[ large ]
                    text-black
                    laptop:shrink-0 laptop:w-2/3">
            <?php if (!empty(carlo_get('description'))): ?><?= carlo_get("description") ?><?php endif; ?>
        </div>
        <?php if(!empty(carlo_get('cta')['link']) && !empty(carlo_get('cta')['label'])): ?>
        <aside class="hidden
                      laptop:block">
          <?php if (carlo_get("cta")) {
              $template = carlo_get("cta_template") ?? '';
              carlo_render("components/cta".$template, carlo_get('cta'));
          } ?>
        </aside>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </header>
<?php endif; ?>
    <?php if (!empty($slides)): ?>
      <?php $cols =
          count($slides) === 2 || count($slides) === 4
              ? "laptop:grid-cols-2"
              : "laptop:grid-cols-3"; ?>
    <ul class="flex flex-col <?= $gap_class ?>
              laptop:grid <?= $cols ?>">
      <?php foreach ($slides as $slide): ?>
        <?php if ($bg_primary) $slide['style'] = 'card__primary'; ?>
        <li class="[ slide__primary ]">
          <?php carlo_render($slide_template, $slide); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <aside class="flex justify-center
                  laptop:hidden">
      <?php if (carlo_get("cta")) {
          carlo_render("components/cta", carlo_get('cta'));
      } ?>
    </aside>

  </section>


<?php if ($bg_primary): ?>
</div>
<?php endif; ?>
