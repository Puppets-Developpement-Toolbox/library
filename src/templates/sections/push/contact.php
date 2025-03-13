


<section class="[ section ]
                relative
                bg-gray-light
                laptop:grid laptop:grid-cols-12">

  <div class="flex flex-col gap-6 relative z-10 px-8 py-12
              text-center
              laptop:col-span-8 laptop:col-start-3 laptop:px-0 laptop:py-14">
    <h2 class="[ h2 ]"><?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("title"))); ?></h2>
    <div class="text-primary">
      <?= carlo_get('description') ?>
    </div>
    <div class="flex justify-center">
      <?php
        $template = carlo_get('cta_template') ?? 'secondary';
        carlo_render("components/cta:$template", array_merge(carlo_get('cta'), ['cta_hover'=> true]));
      ?>
    </div>
  </div>

  <aside class="overflow-hidden absolute z-0 inset-0">
    <div class="absolute -top-[61px] -left-[45px] size-27.5
                laptop:size-35 laptop:left-12 laptop:-top-[26px]">
      <svg viewBox="0 0 40 40"><use href="#svg__logo-emblem"></use></svg>
    </div>
    <div class="absolute -bottom-[28px] -right-[31px] size-22.5
                laptop:size-26 laptop:right-12 laptop:-bottom-[38px]">
      <svg viewBox="0 0 40 40"><use href="#svg__logo-emblem"></use></svg>
    </div>
  </aside>

</section>
