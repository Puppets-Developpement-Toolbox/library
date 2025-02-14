


<div class="flex flex-col gap-6 p-8
            border-1 border-white
            group-even/insight:bg-white group-even/insight:text-primary">
  <header class="[ h5 ]
                flex flex-col gap-2 pb-4
                border-b-1 border-b-white
                text-white uppercase
                group-even/insight:border-primary group-even/insight:text-primary">
    <span><?= carlo_get('title') ?></span>
    <small class="text-primary-light"><?= carlo_get('details') ?></small>
  </header>
  <div class="[ texte ]">
    <?= carlo_get('content') ?>
  </div>
</div>
