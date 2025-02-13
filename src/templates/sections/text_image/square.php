


<?php
// Inversion distribution image/texte
$revert = false;

$slides = carlo_get("slides");

?>

<section class="[ section has-slider ]">
  <div class="group/section
              bg-primary
              laptop:grid laptop:grid-cols-12 laptop:aspect-[2/1]">

    <div class="laptop:col-span-6
                <?php if (!$revert): ?>
                  laptop:col-start-1 laptop:order-1
                <?php else: ?>
                  laptop:col-start-7 laptop:order-2
                <?php endif; ?>">

      <?php if ($slides): ?>
      <section class="relative">
        <div class="swiper">
          <ul class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
            <li class="swiper-slide">
              <figure class="[ img__cover ]
                            aspect-square">
                <?= $slide["image"] ?>
              </figure>
            </li>
            <?php endforeach ?>
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
                        group-hover/btn:-translate-x-1/2"><use xlink:href="#svg__arrow"></use></svg>
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
                        group-hover/btn:translate-x-1/2"><use xlink:href="#svg__arrow"></use></svg>
          </button>
          <div class="[ swiper-pagination ]
                      !bottom-0 translate-y-full"></div>
        </aside>
      </section>
      <?php endif; ?>

    </div>

    <section class="group/on-primary
                    px-8 py-10 pt-12
                    text-white
                    laptop:col-span-6 laptop:flex laptop:gap-5 laptop:overflow-y-auto laptop:items-start laptop:px-0 laptop:py-14
                    <?php if (!$revert): ?>
                      laptop:col-start-8 laptop:col-end-12 laptop:order-2
                    <?php else: ?>
                      laptop:col-start-2 laptop:col-end-6
                    <?php endif; ?>
                    [scrollbar-width:none]">
      <div class="flex flex-col gap-6 laptop:gap-8
                  text-white">
        <header class="[ kicker-subtitle ]
                      pb-6
                      border-b-[1px] border-b-white
                      text-white">
          <?php if (carlo_get("surtitle")): ?>
          <p class="mb-2"><?= carlo_get("surtitle") ?></p>
          <?php endif; ?>
          <h2 class="[ h2 ]
                    text-white">
            <?= carlo_get("title") ?>
          </h2>
        </header>
        <div class="[ text ]"><?= carlo_get("description") ?></div>
        <?php if (carlo_get("cta")) {
            carlo_render("components/cta", [
                "link" => "#",
                "label" => "En savoir plus",
            ]);
        } ?>
      </div>
    </section>

  </div>
</section>
