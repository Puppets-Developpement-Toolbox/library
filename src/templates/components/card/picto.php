


<div class="[ card ]
            group/card
            flex items-center gap-4 h-full px-10 py-8
            bg-gradient-to-r from-white from-50% to-primary to-50% bg-right bg-[size:202%] bg-no-repeat
            border-1 border-gray-light
            transition-all duration-300
            hover:bg-left">
  <figure class="shrink-0 size-10.5
                text-accent
                laptop:size-14">
    <svg viewBox="0 0 56 56"><use xlink:href="#svg__<?= carlo_get('picto') ?>"></use></svg>
  </figure>
  <div class="[ h4 ]
              m-0 text-white
              transition duration-300
              group-hover/card:text-primary">
    <?= carlo_get('content') ?>
  </div>
</div>
