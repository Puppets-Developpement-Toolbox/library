<section class="section push <?= carlo_get('deco_color') ?>">
  <div class="text">
    <h2 class="t2">
      <?= carlo_get('title') ?>
    </h2>
    <div class="desc">
    <?= carlo_get('description') ?>
    </div>
    <!-- direct output -->
    <a href="<?= carlo_get('cta.link') ?>" class="btn">
      <span><?= carlo_get('cta.label') ?></span>
    </a>
    <!-- use template component/cta -->
    <?php carlo_render('components/cta', carlo_get('cta')) ?>
  </div>
  <div class="image">
    <?= carlo_get('image') ?>
  </div>
</section>