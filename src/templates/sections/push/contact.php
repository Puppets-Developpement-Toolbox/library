


<section class="[ section ]
                relative
                bg-gray-light
                laptop:grid laptop:grid-cols-12">

  <div class="flex flex-col gap-6 relative px-8 py-12
              text-center
              laptop:col-span-8 laptop:col-start-3 laptop:px-0 laptop:py-14">
    <h2 class="[ h2 ]"><?= carlo_get('title') ?></h2>
    <div class="text-primary">
      <?= carlo_get('description') ?>
    </div>
    <div class="flex justify-center">
      <?php
        // TODO: Proposer dans l'admin un CTA Primary ou Secondary
        carlo_render("components/cta:secondary", [
          "link" => "#",
          "label" => "En savoir plus sur nos méthodes",
        ]);
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
