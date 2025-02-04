<aside class="section actus-last">
  <div class="desc">
    <h2 class="t2">
      <?= carlo_get('title') ?>
    </h2>
    <?= carlo_get('description') ?>
    <p>
      Depuis plus de 10 ans, Innovation Factory a accompagné de nombreuses marques et mené de nombreux projets dans le cadre de ses partenariats. Découvrez toutes nos actualités et des extraits de nos projets récents.
    </p>
  </div>
  
  <div class="actus-list">
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
    <a href="#" class="btn">
      <span>
        Voir plus d'articles
      </span>
    </a>
  </div>
</aside>