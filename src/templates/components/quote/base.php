


<figure class="relative flex flex-col gap-4 p-6
              bg-gray-light
              laptop:col-span-10 laptop:col-start-2 laptop:flex-row laptop:flex-wrap laptop:gap-6">

  <span class="block size-8
              text-primary-light
              laptop:size-10"><svg viewBox="0 0 56 56"><use xlink:href="#svg__quote"></use></svg></span>

  <blockquote class="[ quote ]
                    laptop:w-full laptop:mx-10">
    <?= carlo_get('quote') ?>
  </blockquote>

  <?php if (carlo_get('author')): ?>
  <!-- <span class="size-2
              rounded-full bg-accent"></span> -->
  <figcaption class="before:block before:size-2 before:mb-4 before:rounded-full before:bg-accent
                    laptop:w-full laptop:mx-10 laptop:before:mb-6">
    <small><strong class="block"><?= carlo_get('author') ?></strong><?= carlo_get('charge') ?></small>
  </figcaption>
  <?php endif ?>

  <span class="block size-8 rotate-180 ml-auto
              text-primary-light
              laptop:size-10"><svg viewBox="0 0 56 56"><use xlink:href="#svg__quote"></use></svg></span>

</figure>
