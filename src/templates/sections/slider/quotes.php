


<?php 

  $slides = carlo_get('slides');

?>
<div class="laptop:relative laptop:overflow-x-clip
            laptop:before:block laptop:before:absolute laptop:before:z-10 laptop:before:top-0 laptop:before:left-0 laptop:before:w-1/3 laptop:before:h-full laptop:before:bg-white">
  <section class="[ section has-slider slider-horizontal ]">

    <div class="laptop:grid laptop:grid-cols-12">

      <header class="relative z-10 mb-10
                    laptop:col-span-6 laptop:mb-0 laptop:bg-white">
        <div class="laptop:flex laptop:justify-between laptop:gap-6 laptop:w-2/3 laptop:mx-auto laptop:pt-10 laptop:border-t-1 laptop:border-t-black">

          <div class="flex flex-col gap-6">
            <h2 class="[ h2 ]"><?= carlo_get('title') ?></h2>
            <div><?= carlo_get('subtitle') ?></div>
          </div>
          
          <aside class="[ swiper__dashboard ]
                        flex justify-end gap-2
                        laptop:flex-col laptop:justify-start">
            <button type="button"
                    class="[ swiper-button-prev ]
                          group/btn
                          !static !size-12 !m-0
                          rounded-full border-1 border-primary bg-transparent
                          !text-primary
                          after:!content-none
                          laptop:inline-block laptop:!left-6">
              <svg viewBox="0 0 24 24"
                    class="!size-6
                          transition-transform duration-300
                          group-hover/btn:-translate-x-1/2"><use xlink:href="#svg__arrow"></use></svg>
            </button>
            <button type="button"
                    class="[ swiper-button-next ]
                          group/btn
                          !static !size-12 !m-0
                          rounded-full border-1 border-primary bg-transparent
                          !text-primary
                          after:!content-none
                          laptop:inline-block laptop:!right-6">
              <svg viewBox="0 0 24 24"
                    class="!size-6 rotate-180
                          transition-transform duration-300
                          group-hover/btn:translate-x-1/2"><use xlink:href="#svg__arrow"></use></svg>
            </button>
          </aside>
        
        </div>
      </header>

      <?php if ($slides): ?>
      <section class="relative z-0
                      laptop:col-span-6">

        <div class="swiper
                    !overflow-visible">
          <ul class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
            <li class="swiper-slide">
              <div class="select-none">
                <?php carlo_render("components/quote", [
                      "quote" => $slide['quote'],
                      "author" => $slide['author'],
                      "charge" => $slide['charge'],
                  ]);
                ?>
              </div>
            </li>
            <?php endforeach ?>
            </ul>
        </div>

      </section>
      <?php endif ?>

    </div>
  </section>
</div>
