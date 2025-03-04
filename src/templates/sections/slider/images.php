


<?php

  $slides = carlo_get('slides');
  $slides = array_filter($slides, function($slide){
      return $slide['image'] !== false;
  });
?>

<div class="bg-primary text-white
            group/on-primary">
  <div class="overflow-x-clip">
    <section class="[ section has-slider slider-cards ]
                    flex flex-col gap-8 relative
                    group-[&]/on-primary:py-16 group-[&]/on-primary:bg-transparent group-[&]/on-primary:laptop:py-30
                    laptop:">

      <header>
        <h2 class="[ h2 ]
                  mb-6
                  group-[&]/on-primary:text-white
                  laptop:col-span-12">
          <?= carlo_get("title") ?>
        </h2>

        <div class="laptop:grid laptop:grid-cols-12">
          <div class="laptop:col-span-8">
            <?= carlo_get("description") ?>
          </div>
          <aside class="hidden
                        laptop:col-span-4 laptop:flex laptop:justify-end laptop:gap-4">
            <div class="[ swiper__dashboard ]
                        flex gap-2">
              <button type="button"
                      class="[ swiper-button-prev ]
                            group/btn
                            inline-block !static !left-6 !size-12 !m-0
                            rounded-full border-1 border-primary bg-transparent
                            !text-primary
                            group-[&]/on-primary:!text-white group-[&]/on-primary:border-white
                            after:!content-none">
                <svg viewBox="0 0 24 24"
                      class="!size-6
                            transition-transform duration-300
                            group-hover/btn:-translate-x-1/2"><use href="#svg__arrow"></use></svg>
              </button>
              <button type="button"
                      class="[ swiper-button-next ]
                            group/btn
                            inline-block !static !right-6 !size-12 !m-0
                            rounded-full border-1 border-primary bg-transparent
                            !text-primary
                            group-[&]/on-primary:!text-white group-[&]/on-primary:border-white
                            after:!content-none">
                <svg viewBox="0 0 24 24"
                      class="!size-6 rotate-180
                            transition-transform duration-300
                            group-hover/btn:translate-x-1/2"><use href="#svg__arrow"></use></svg>
              </button>
              <div class="[ swiper-pagination ]
                          flex justify-center items-center !bottom-20 translate-y-full"></div>
            </div>
            <?php if (carlo_get("cta")) {
                carlo_render("components/cta", carlo_get("cta"));
            } ?>
          </aside>
        </div>
      </header>





      <?php if (!empty($slides)): ?>
      <div>
        <section class="swiper
                    !overflow-visible">
          <ul class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
            <li class="swiper-slide
                      !w-72.5 !h-auto
                      laptop:!w-175">
              <figure class="[ img__cover ]
                            aspect-[6/5]">
                <?= carlo_img($slide['image'], '435x363', ['1050x876']) ?>
              </figure>
            </li>
            <?php endforeach ?>
            </ul>
        </section>
      </div>
      <?php endif; ?>

      <aside class="flex justify-center
                    laptop:hidden">
        <?php if (carlo_get("cta")) {
            carlo_render("components/cta", carlo_get('cta'));
        } ?>
      </aside>

    </section>
  </div>
</div>
