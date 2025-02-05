


<header class="[ section__hero ]
              group/hero
              laptop:relative laptop:mt-0 laptop:px-10">

  <figure class="[ img__cover ]
                aspect-[var(--ratio-img-mobile)]
                laptop:aspect-[var(--ratio-img-laptop)]">
    <?= carlo_get('image') ?>
  </figure>
  


  <section class="m-6 mt-12
                  bg-white
                  laptop:absolute laptop:right-0 laptop:top-1/2 laptop:-translate-y-1/2 laptop:w-[65%] laptop:m-0 laptop:px-[100px] laptop:py-[64px]
                  wide:w-1/2 wide:px-[120px]">

    <aside class="my-6
                  border-b-[1px] border-b-black">
      <button type="button"
              class="group/btn
                    flex items-center gap-2 relative p-3
                    transition-colors duration-300
                    hover:text-primary">
        <span class="size-6
                    transition-transform duration-300
                    group-hover/btn:-translate-x-1/2">
          <svg viewBox="0 0 24 24"><use xlink:href="#svg__arrow"></use></svg>
        </span>
        <small>Nos réalisations</small>
      </button>
    </aside>
    
    <h1 class="[ h1 ]">
      <span class="[ kicker-subtitle ]
                  mb-2"><?= carlo_get('surtitle') ?></span>
      <?= carlo_get('title') ?>
    </h1>

    <aside class="my-6
                  border-t-[1px] border-t-black
                  laptop:grid laptop:grid-cols-7">
      <div class="laptop:col-span-6 laptop:col-start-2">
        <p class="[ large ]
                  my-6"><?= carlo_get('push_cta_title') ?></p>

        <?php if (carlo_get('cta') || carlo_get('cta_contact')): ?>
        <ul class="flex flex-col gap-3
                  laptop:flex-row">
          <?php if (carlo_get('cta')): ?>
          <li><?php carlo_render('components/cta', ['link' => '#', 'label' => 'En savoir plus']) ?></li>
          <?php endif ?>

          <?php if (carlo_get('cta_contact')): ?>
          <li><?php carlo_render('components/cta_link', ['link' => '#', 'label' => 'Contactez-nous']) ?></li>
          <?php endif ?>
        </ul>
        <?php endif ?>
      </div>

    </aside>

  </section>

</header>
