


<?php if(carlo_get('picto')): ?>
<div class="[ card ]
            group/card
            flex items-center gap-4 h-full px-10 py-8
            bg-gradient-to-r from-primary from-50% to-white to-50% bg-right bg-[size:202%] bg-no-repeat
            border-1 border-primary
            transition-all duration-300
            hover:bg-left
            group-[&]/on-primary:from-white group-[&]/on-primary:to-primary group-[&]/on-primary:border-gray-light">
  <figure class="shrink-0 size-10.5
                text-accent
                laptop:size-14">
    <svg viewBox="0 0 56 56"><use href="#svg__<?= carlo_get('picto') ?>"></use></svg>
  </figure>
  <div class="[ h4 ]
              m-0 
              transition duration-300
              <?php if (carlo_get('style') === 'card__primary'): ?> text-white group-hover/card:text-primary
              <?php else: ?> text-primary group-hover/card:text-white
              <?php endif ?>">
    <?= carlo_get('content') ?>
  </div>
</div>
<?php endif; ?>
