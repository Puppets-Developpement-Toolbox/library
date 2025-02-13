


<?php $logos = carlo_get('logos'); ?>

<section class="[ section__partners ]">
  <ul class="[ partners ]
            overflow-x-clip relative my-14 h-25">
    <?php foreach ($logos as $logo): ?>
    <li data-xmin="0"
        class="[ partner ]
              flex justify-center items-center aspect-[19/10] w-47.5
              absolute top-0 px-7.5 py-6
              border-1 border-black">
      <img src="<?= $logo['logo'] ?>"
          alt="<?= $logo['brand'] ?>"
          class="size-full object-contain">
    </li>
    <?php endforeach ?>
  </ul>
</section>
