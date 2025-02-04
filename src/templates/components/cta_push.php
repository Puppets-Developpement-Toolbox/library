


<a href="<?= carlo_get('link') ?>">
  <button type="button"
          class="group/btn
                flex justify-center items-center w-auto px-6 py-3
                rounded-full bg-gradient-to-r from-white from-50% to-primary to-50% border-[1px] bg-right bg-[size:_200%] border-primary
                text-white
                transition-all duration-300
                group-[.bg-primary]/section:border-white
                group-[&]/hero:w-full group-[&]/hero:laptop:w-auto
                hover:bg-left hover:text-primary
                group-[&]/section:laptop:mt-8">
    <span class="shrink-0 size-6 text-accent
                transition-transform duration-300
                group-hover/btn:rotate-180">
      <svg viewBox="0 0 24 24"><use xlink:href="#svg__more"></use></svg>
    </span>
    <span><?= carlo_get('label') ?></span>
  </button>
</a>
