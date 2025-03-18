

<?php if(carlo_get('link') || carlo_get('label')): ?>
<a href="<?= carlo_get('link') ?>">
  <button type="button"
          class="group/btn
                flex justify-center items-center gap-1 w-auto py-3
                text-primary font-bold
                hover:text-black">
    <span class="shrink-0 size-6 text-accent
                transition-transform duration-300
                <?= carlo_get('icon')? '' : 'group-hover/btn:rotate-180' ?>">
                    <svg viewBox="0 0 24 24">
                        <use href="#svg__<?= carlo_get('icon')? 'link' : 'more' ?>"></use>
                    </svg>
    </span>
    <span><?= carlo_get('label') ?></span>
  </button>
</a>
<?php endif; ?>
