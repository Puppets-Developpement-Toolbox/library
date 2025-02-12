


<?php 

  $slides = carlo_get('slides');

?>

<div class="laptop:relative laptop:overflow-x-clip
            before:block before:absolute before:z-10 before:top-0 before:left-0 before:w-1/3 before:h-full before:bg-white">

  <section class="[ section has-slider slider-horizontal ]">

    <div class="laptop:grid laptop:grid-cols-12">
    
      <header class="relative z-10 mb-10
                    laptop:col-span-5 laptop:mb-0 laptop:bg-white">
        <div class="laptop:flex laptop:justify-between laptop:gap-6 laptop:mr-10 laptop:pt-10 laptop:w-5/6 laptop:border-t-1 laptop:border-t-black">

          <h2 class="[ h2 ]"><?= carlo_get('title') ?></h2>
          
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
                      laptop:col-span-7 laptop:col-start-6">

        <div class="swiper
                    !overflow-visible">
          <ul class="swiper-wrapper">
            <?php $num = 0 ?>
            <?php foreach ($slides as $slide): ?>
              <?php $style = ($num % 2 === 0) ? 'bg-gray-light' : 'border-1 border-black' ?>
              <li class="swiper-slide
                        !size-auto">
                <div class="w-61 h-full px-10 py-12
                            <?= $style ?>">
                  <?= $slide['text'] ?>
                </div>
              </li>
              <?php $num++ ?>
            <?php endforeach ?>
            </ul>
        </div>

      </section>
      <?php endif ?>

    </div>
  </section>

</div>

