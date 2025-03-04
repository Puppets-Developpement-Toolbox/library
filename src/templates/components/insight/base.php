

<?php if(carlo_get('details') || carlo_get('content')): ?>
<div class="flex flex-col gap-6 p-8
            border-1 border-white
            group-even/insight:bg-white group-even/insight:text-primary">
<?php if(!empty(carlo_get('title')) || !empty(carlo_get('details'))): ?>
  <header class="[ h5 ]
                flex flex-col gap-2 pb-4
                border-b-1 border-b-white
                text-white uppercase
                group-even/insight:border-primary group-even/insight:text-primary
                laptop:flex-row laptop:justify-between laptop:gap-4">
    <span>
    <span class="mb-0"><?= carlo_get('title') ?></span>
    <small class="text-primary-light"><?= carlo_get('details') ?></small>
    </span>
  </header>
<?php endif; ?>
  <div class="[ text ]">
    <?= carlo_get('content') ?>
  </div>
</div>
<?php endif; ?>
