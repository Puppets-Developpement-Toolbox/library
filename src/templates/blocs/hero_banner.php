


<header class="[ section ]
              laptop:relative laptop:mt-0 laptop:px-10">

  <figure class="[ img__cover ]
                aspect-[375/450]
                laptop:aspect-[1840/940]">
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
        <small>Nos réalisations</span>
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

        <?php if (carlo_get('cta_push') || carlo_get('cta_contact')): ?>
        <ul class="flex flex-col gap-3
                  laptop:flex-row">
          <?php if (carlo_get('cta_push')): ?>
          <li>
            <a href="<?= carlo_get('cta_push')['link'] ?>">
              <button type="button"
                      class="group/btn
                            flex justify-center items-center gap-1 w-full px-6 py-3
                            rounded-full bg-gradient-to-r from-white from-50% to-primary to-50% border-[1px] bg-right bg-[size:_200%] border-primary
                            text-white
                            transition-all duration-300
                            hover:bg-left hover:text-primary">
                <span class="shrink-0 size-6 text-accent
                            transition-transform duration-300
                            group-hover/btn:rotate-180">
                  <svg viewBox="0 0 24 24"><use xlink:href="#svg__more"></use></svg>
                </span>
                <span><?= carlo_get('cta_push')['label'] ?></span>
              </button>
            </a>
          </li>
          <?php endif ?>

          <?php if (carlo_get('cta_contact')): ?>
          <li>
            <a href="<?= carlo_get('cta_contact')['link'] ?>">
              <button type="button"
                      class="group/btn
                            flex justify-center items-center gap-1 w-full px-6 py-3
                            rounded-full bg-gradient-to-r from-primary from-50% to-white to-50% border-[1px] bg-right bg-[size:_200%] border-primary
                            transition-all duration-300
                            hover:bg-left hover:text-white">
                <span><?= carlo_get('cta_contact')['label'] ?></span>
              </button>
            </a>
          </li>
          <?php endif ?>
        </ul>
        <?php endif ?>
      </div>

    </aside>

  </section>

</header>
