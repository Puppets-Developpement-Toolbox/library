


<a href="<?= carlo_get('link') ?>">
  <button type="button"
          class="group/btn
                flex justify-center items-center gap-1.5 w-auto px-6 py-3
                rounded-full bg-gradient-to-r from-white from-50% to-primary to-50% bg-right bg-[size:202%] bg-no-repeat border-[1px] border-primary
                text-white
                transition-all duration-300
                hover:bg-left hover:text-primary
                group-[.bg-primary]/section:border-white
                group-[&]/on-primary:bg-gradient-to-l group-[&]/on-primary:border-white group-[&]/on-primary:text-primary group-[&]/on-primary:hover:text-white
                group-[&]/hero:w-full group-[&]/hero:laptop:w-auto">
    <span class="shrink-0 size-6 text-accent
                transition-transform duration-300
                group-hover/btn:rotate-180">
      <svg viewBox="0 0 24 24"><use xlink:href="#svg__more"></use></svg>
    </span>
    <span><?= carlo_get('label') ?></span>
  </button>
</a>
