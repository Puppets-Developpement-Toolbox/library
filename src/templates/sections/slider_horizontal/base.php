


<?php 

  $slides = carlo_get('slides');

?>

<section class="[ section has-slider slider-horizontal ]">
  
  <header class="mb-10">
    <h2 class="[ h2 ]"><?= carlo_get('title') ?></h2>
    
    <aside class="[ swiper__dashboard ]
                  flex justify-end gap-2  ">
      <button type="button"
              class="[ swiper-button-prev ]
                    group/btn
                    !static !size-12 !m-0
                    rounded-full border-1 border-primary bg-transparent
                    !text-primary
                    after:!content-none
                    laptop:inline-block laptop:!size-16 laptop:!left-6">
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
                    laptop:inline-block laptop:!size-16 laptop:!right-6">
        <svg viewBox="0 0 24 24"
              class="!size-6 rotate-180
                    transition-transform duration-300
                    group-hover/btn:translate-x-1/2"><use xlink:href="#svg__arrow"></use></svg>
      </button>
    </aside>
  </header>

  <?php if ($slides): ?>
  <section class="relative">

    <div class="swiper
                !overflow-visible">
      <ul class="swiper-wrapper">
        <?php $num = 0 ?>
        <?php foreach ($slides as $slide): ?>
          <?php $style = ($num % 2 === 0) ? 'bg-gray-light' : 'border-1 border-black' ?>
          <li class="swiper-slide
                    !w-auto">
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
</section>

