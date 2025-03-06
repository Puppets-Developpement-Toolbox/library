

<?php if(carlo_get('details') || carlo_get('content')): ?>
<div class="flex flex-col gap-4 p-8
            border-1 border-white
            group-odd/insight:bg-white group-odd/insight:text-primary">
<?php if(!empty(carlo_get('title')) || !empty(carlo_get('details'))): ?>
  <header class="[ h5 ]
                flex flex-col gap-2 pb-4
                border-b-1 border-b-white
                text-white uppercase
                group-odd/insight:border-primary group-odd/insight:text-primary
                laptop:flex-row laptop:justify-between laptop:items-center laptop:gap-4">
    <span class="mb-0 laptop:flex-1 laptop:max-w-[70%]"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?></span>
    <small class="text-primary-light"><?= carlo_get('details') ?></small>
  </header>
<?php endif; ?>
  <div class="[ text ]">
    <?= carlo_get('content') ?>
  </div>
</div>
<?php endif; ?>
