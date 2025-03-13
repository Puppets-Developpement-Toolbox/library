

<?php if(carlo_get('link') || carlo_get('label')): ?>
<a href="<?= carlo_get('link') ?>">
  <button type="button"
          class="group/btn
                flex justify-center items-center gap-1 w-auto px-6 py-3
                group-[&]/hero:w-full
                rounded-full bg-gradient-to-r from-primary from-50% to-white to-50% border-[1px] bg-right bg-[size:_200%] border-primary
                transition-all duration-300
                group-[&]/hero:laptop:w-auto
                text-primary
                <?php if(!empty(carlo_get('cta_hover'))) : ?>hover:bg-left hover:text-white<?php endif; ?>">
    <span class="-mt-0.5"><?= carlo_get('label') ?></span>
  </button>
</a>
<?php endif; ?>
