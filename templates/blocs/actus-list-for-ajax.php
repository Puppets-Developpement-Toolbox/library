<?php $cards = carlo_get('cards');
  foreach($cards as $card) : ?>
  <div class="actu">
    <div class="actu-cont">
      <div class="image">
        <?php echo $card['image']; ?>
      </div>
      <div class="desc">
        <div class="middle">
          <div class="type">
             <!-- manque la data dans structure.yml -->
             Innovation
          </div>
          <h3 class="t3">
            <?php echo $card['title']; ?>
          </h3>
          <div class="text no-mobile">
            <?php echo $card['description']; ?>
          </div>
          <div class="actions">
            <?php carlo_render('components/cta', $card['cta']); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
<!-- only when there are no more results -->
<div id="end"></div>