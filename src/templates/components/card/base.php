

<div class="[ card ]
            group/card
            flex flex-col h-full">
<?php $link = carlo_get('link'); if(!empty($link)): ?>
    <a href="<?=$link?>">
<?php endif; ?>
<?php if(!empty(carlo_get('image'))): ?>
    <figure class="[ img__cover ]
                shrink-0 aspect-[8/5] overflow-clip !m-0">
    <div class="aspect-[8/5] scale-115 h-full
                transition-transform duration-500
                group-hover/card:scale-100">
      <?= carlo_img('image', '630x394') ?>
    </div>
  </figure>
<?php endif; ?>
  <div class="flex-1 flex flex-col gap-6 mb-[1px] p-8
              border-1 <?=!carlo_get('border')? 'border-t-0' : '' ?> border-black
              group-[&]/on-primary:bg-white group-[&]/on-primary:border-none
              laptop:px-12">
    <header class="flex flex-col gap-2
                  after:transition-transform after:duration-500
                  after:block after:origin-left after:w-full after:h-[1px] after:mt-4 after:bg-black
                  group-hover/card:after:scale-x-85">
      <h3 class="[ h3 ]"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?></h3>
      <span class="[ kicker-subtitle ]"><?= carlo_get('subtitle') ?></span>
    </header>
    <div class="[ text ]
                flex-1
                group-[&]/on-primary:text-primary">
        <?= carlo_get('content') ?>
        <?php if(carlo_get('cta')):?>
        <?php carlo_render('components/cta:tertiary', carlo_get('cta')) ?>
        <?php endif; ?>
    </div>
  </div>
<?php if(!empty($link)): ?>
</a>
<?php endif; ?>
</div>
