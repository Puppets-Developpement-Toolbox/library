


<section class="[ section ]">
  <div class="laptop:grid laptop:grid-cols-12 laptop:gap-0">

    <figure class="[ img__cover ]
                  aspect-[var(--ratio-img-mobile)] mb-12
                  laptop:col-span-6 laptop:mb-0">
      <?= carlo_get('image') ?>
    </figure>
    
    <section class="laptop:col-span-5 laptop:col-end-13 laptop:flex laptop:items-center">
      <div>
        <header class="mb-6">
          <?= carlo_get('surtitle') ?>
          <h2 class="[ h2 ]">
            <?= carlo_get('title') ?>
          </h2>
        </header>
        <?= carlo_get('description') ?>
        <?php if (carlo_get('cta_push')) carlo_render('components/cta_push', ['link' => '#', 'label' => 'En savoir plus']) ?>
      </div>
    </section>
  
  </div>
</section>
