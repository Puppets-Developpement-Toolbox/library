


<div class="[ card ]
            group/card
            flex flex-col h-full">
  <figure class="[ img__cover ]
                shrink-0 aspect-[8/5] overflow-clip">
    <div class="scale-115 h-full
                transition-transform duration-500
                group-hover/card:scale-100">
      <?= carlo_get('image') ?>
    </div>
  </figure>
  <div class="flex-1 flex flex-col gap-6 p-8
              border-1 border-t-0 border-black
              group-[&]/on-primary:bg-white group-[&]/on-primary:border-none">
    <header class="flex flex-col gap-2
                  after:transition-transform after:duration-500
                  after:block after:origin-left after:w-full after:h-[1px] after:mt-4 after:bg-black
                  group-hover/card:after:scale-x-85">
      <h3 class="[ h3 ]"><?= carlo_get('title') ?></h3>
      <span class="[ kicker-subtitle ]"><?= carlo_get('subtitle') ?></span>
    </header>
    <div class="[ text ]
                flex-1
                group-[&]/on-primary:text-primary">
      <?= carlo_get('content') ?>
    </div>
    <?php carlo_render('components/cta:tertiary', ['link' => 'https://www.apple.com/fr', 'label' => 'Test']) ?>
  </div>
</div>
