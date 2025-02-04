<?php 
$variant = carlo_get('variant') ?: 'no-picto';
// no-picto, cols-4, cols-3
?>

<section class="section columns <?php echo $variant; ?>">

  <h2 class="t2"><?php echo carlo_get('title'); ?></h2>
  
  <div class="cols">
    <?php $columns = carlo_get('columns');
    foreach($columns as $column): ?>
    <div class="col">
      <?php if(isset($column['image'])) : ?>
      <div class="image">
        <?php echo $column['image']; ?>
      </div>
      <?php endif ?>
      <h3 class="t3">
        <?php echo $column['title']; ?>
      </h3>
      <?php if(isset($column['subtitle'])) : ?>
      <h4 class="t4">
        <?php echo $column['subtitle']; ?>
      </h4>
      <?php endif; ?>
      
      <?php echo $column['description']; ?> 
    </div>
    <?php endforeach; ?>
  </div>
</section>