


<?php

  $partners = carlo_get('partners');

?>
<div class="overflow-x-clip laptop:relative">
  <section class="[ section has-slider slider-partners ]
                  group-[&]/super-section:bg-gray-light">
    <div class="laptop:grid laptop:grid-cols-12">





      <header class="relative z-10 mb-10
                    laptop:col-span-6 laptop:mb-0 laptop:bg-white
                    group-[&]/super-section:bg-gray-light group-[&]/super-section:laptop:bg-gray-light">
        <div class="laptop:flex laptop:justify-between laptop:gap-6 laptop:w-2/3 laptop:mx-auto laptop:pt-10 laptop:border-t-1 laptop:border-t-black">

          <div class="flex flex-col gap-6">
            <h2 class="[ h2 ]"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?></h2>
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
                          group-hover/btn:-translate-x-1/2"><use href="#svg__arrow"></use></svg>
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
                          group-hover/btn:translate-x-1/2"><use href="#svg__arrow"></use></svg>
            </button>
          </aside>


        </div>
      </header>
      <div class="laptop:col-span-6">
        <?php carlo_render("components/quote", [
            "quote" => str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("quote"))),
            "author" => carlo_get('author'),
            "charge" => carlo_get('charge'),
          ]);
        ?>
      </div>





      <?php if ($partners): ?>
      <div class="my-12
                  laptop:col-span-12">
        <section class="[ swiper ]
                      !overflow-visible">
          <ul class="[ swiper-wrapper ]">
            <?php foreach ($partners as $partner): ?>
            <li class="[ swiper-slide ]
                       !w-47.5">
              <figure class="flex justify-center items-center aspect-[19/10] px-7.5 py-6
                            border-1 border-black">
                <?php
                if (is_int($partner['logo'])) {
                    echo carlo_img($partner['logo'], 'size-full', 'object-contain');
                } else {
                    echo '<img src="' . $partner['logo'] . '"
                        alt="' . $partner['brand'] . '"
                        class="size-full object-contain">';
                }
                ?>
              </figure>
            </li>
            <?php endforeach ?>
            </ul>
        </section>
      </div>
      <?php endif ?>





    </div>
  </section>
</div>
