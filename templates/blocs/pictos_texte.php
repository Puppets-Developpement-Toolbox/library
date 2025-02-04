<section class="section picto-text">
  <h2 class="t2">
    <?php echo carlo_get('title'); ?>
  </h2>
  
  <div class="picto-text-list" role="list">
  <?php $pictos = carlo_get('pictos');
    foreach($pictos as $picto): ?>
    <div class="picto-text-item">
      <div class="image">
        <?php echo $picto['image']; ?>
      </div>
      <h3 class="t3">
      <?php echo $picto['title']; ?>
      </h3>
      <?php echo $picto['description']; ?> 
    </div>
    <?php endforeach; ; ?>
  </div>
  
  <div class="actions">
    <?php carlo_render('components/cta', carlo_get('cta')); ?>
  </div>
  
</section>
