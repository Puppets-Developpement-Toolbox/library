


<?php

// Inversion distribution image/texte
$revert = carlo_get('revert');

$slides = carlo_get("slides");
$slides = array_filter($slides, function($slide){
    return $slide['image'] !== false;
});
?>

<section <?= !empty(carlo_get('ancre')) ? 'id="' . carlo_get('ancre') . '"' : '' ?> class="[ section has-slider ]
                group-[&]/super-section:bg-transparent">
  <div class="group/section
              laptop:grid laptop:grid-cols-12 laptop:items-center">

    <div class="laptop:col-span-6
                <?php if (!$revert): ?>
                  laptop:col-start-1 laptop:order-1
                <?php else: ?>
                  laptop:col-start-7 laptop:order-2
                <?php endif; ?>">


      <section class="relative">
        <div class="swiper">
          <ul class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide): ?>
                <li class="swiper-slide">
                <figure class="[ img__cover ]
                                aspect-[var(--ratio-img-mobile)] !m-0">
                    <?= carlo_img($slide["image"], '1072x1287') ?>
                </figure>
                </li>
                <?php endforeach ?>
            <?php endif ?>
            </ul>
        </div>

        <aside class="swiper__dashboard">
          <button type="button"
                  class="[ swiper-button-prev ]
                        group/btn
                        !size-12 !-left-4
                        rounded-full bg-primary
                        !text-white
                        after:!content-none
                        laptop:!size-16 laptop:!left-6">
            <svg viewBox="0 0 24 24"
                  class="!size-6
                        transition-transform duration-300
                        group-hover/btn:-translate-x-1/2"><use href="#svg__arrow"></use></svg>
          </button>
          <button type="button"
                  class="[ swiper-button-next ]
                        group/btn
                        !size-12 !-right-4
                        rounded-full bg-primary
                        !text-white
                        after:!content-none
                        laptop:!size-16 laptop:!right-6">
            <svg viewBox="0 0 24 24"
                  class="!size-6 rotate-180
                        transition-transform duration-300
                        group-hover/btn:translate-x-1/2"><use href="#svg__arrow"></use></svg>
          </button>
          <div class="[ swiper-pagination ]
                      flex justify-center items-center !-bottom-2 translate-y-full"></div>
        </aside>
      </section>

    </div>

    <section class="group-[&]/super-section:px-8 group-[&]/super-section:py-10
                    pt-12
                    laptop:col-span-6 laptop:flex laptop:gap-5 laptop:items-center laptop:p-0
                    <?php if (!$revert): ?>
                      laptop:col-start-8 laptop:order-2
                    <?php else: ?>
                      laptop:order-1 laptop:col-start-1 laptop:col-end-6
                    <?php endif; ?>">
      <div class="flex flex-col gap-6 laptop:gap-8">
        <header>
          <?php if (carlo_get("surtitle")): ?>
            <span class="[ kicker-subtitle ] mb-2"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("surtitle"))); ?></span>
          <?php endif; ?>
          <h2 class="[ h2 ]">
            <?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?>
          </h2>
          <?php if (carlo_get("subtitle")): ?>
            <span class="[ kicker-subtitle ] mt-4"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("subtitle"))); ?></span>
          <?php endif; ?>
        </header>
        <div class="[ text ]"><?= carlo_get("description") ?></div>
        <?php if (carlo_get('highlight')): ?>
        <div class="p-10
                    bg-gray-light
                    group-[&]/super-section:bg-white">
          <?= carlo_get('highlight') ?>
        </div>
        <?php endif ?>
        <?php if (carlo_get("cta")) : ?>
            <div ><?=carlo_render("components/cta", array_merge(carlo_get("cta"), array('icon' => carlo_get('icon')))); ?></div>
        <?php endif; ?>
      </div>
    </section>

  </div>
</section>
