<div class="section actus-results" id="js-results"> <!-- id="js-results" used by javascript ajax -->

  <div class="actus-list" role="list">
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
  </div>
  
  <div class="actions" id="js-actions">
    <div class="loader">
      <div class="sk-circle" id="js-more-loader">
        <div class="sk-circle1 sk-child"></div>
        <div class="sk-circle2 sk-child"></div>
        <div class="sk-circle3 sk-child"></div>
        <div class="sk-circle4 sk-child"></div>
        <div class="sk-circle5 sk-child"></div>
        <div class="sk-circle6 sk-child"></div>
        <div class="sk-circle7 sk-child"></div>
        <div class="sk-circle8 sk-child"></div>
        <div class="sk-circle9 sk-child"></div>
        <div class="sk-circle10 sk-child"></div>
        <div class="sk-circle11 sk-child"></div>
        <div class="sk-circle12 sk-child"></div>
      </div>
    </div>
    <button type="button" class="btn" id="js-more-results" data-action="index.php?template=actus-list-ajax&action=XXX&post_type=XXXX">
      <span>
        Voir plus
      </span>
    </button>
  </div>
</div>