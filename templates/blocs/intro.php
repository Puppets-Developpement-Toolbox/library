<section class="section intro-page <?= carlo_get('deco_color') ?>">
  <div class="image">
    <!-- exemple tag picture image responsive -->
    <!-- <?= carlo_get('image') ?> -->
    <picture>
      <source 
        srcset="img/intro-full-width_mobile.png" 
        media="(max-width: 500px)"
      >
      <source 
        srcset="img/intro-full-width_mobile.png" 
        media="(min-width: 501px) and (max-width:1023px)"
      >
      <source 
        srcset="img/intro-full-width_desktop.png" 
        media="(min-width: 1024px)"
      >
      <img src="img/intro-full-width_mobile.png" alt="">
    </picture>
  </div>
  <div class="content">
    <h1 class="t1">
      <span>
        <?= carlo_get('title') ?>
      </span>
    </h1>
    <div class="desc">
      <?= carlo_get('description') ?>
    </div>
  </div>
</section>


