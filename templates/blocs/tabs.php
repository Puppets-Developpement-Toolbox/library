<section class="section partners">
  <h2 class="t2"><?php echo carlo_get('title'); ?></h2>

  <?php $partners = carlo_get('partners'); ?>
  <div class="partners-nav">
    <?php foreach($partners as $partner => $value) : ?>
    <button class="partner-nav" data-index="<?php echo ''.$partner; ?>">
      <?php   echo $value['brand_logo']; ?>
      <!--<img src="img/partners-logo-ratp.png" alt="">-->
    </button>
    <?php endforeach; ?>
  </div>
  
  <div class="partners-desc">
    <div class="partners-scroll">
      <?php foreach($partners as $partner => $value) : ?>
      <div class="partner">
        <div class="image">
          <?php //echo $value['image']; ?>
          <img src="img/partners-visu-01.png" alt="">
        </div>
        <div class="desc">
          <h3>
            <?php echo $value['sujet']; ?>
          </h3>
          <?php echo $value['description']; ?>
        </div>
      </div>
      <?php endforeach; ?>    
    </div>
  </div>
  
  <div class="actions">
    <?php carlo_render('components/cta', carlo_get('cta')); ?>
  </div>
</section>
