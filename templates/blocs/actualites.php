<section class="section actus-list borders-top-bottom <?= carlo_get('deco_color') ?>">
  <h2 class="t2">
    <?php echo carlo_get('title'); ?>
  </h2>
  <div class="list-actus" role="list">
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
            <?php echo $card['title']; ?>
            </div>
            <h3 class="t3">
              <?php echo $card['subtitle']; ?>
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
  <div class="actions">
    <!-- manque data dans structure.yml -->
    <a href="#" class="btn">
      <span>
        Voir plus d'articles
      </span>
    </a>
  </div>
</section>

<section class="section actus-list projets-list borders-top-bottom">
  <h2 class="t2">
    <?php echo carlo_get('title'); ?>
  </h2>
  <div class="list-actus" role="list">
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
  <div class="actions">
    <!-- manque data dans structure.yml -->
    <a href="#" class="btn">
      <span>
        Voir plus d'articles
      </span>
    </a>
  </div>
</section>