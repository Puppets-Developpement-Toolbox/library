<section class="section slider-hero">
  <div class="hero-slider">
    <?php 
    $slides = carlo_get('slides');
    foreach($slides as $slide): ?>
    <div class="slide">
      <div class="image-full-width">
       <?php echo $slide['image']; ?>
      </div>
      <div class="content">
        <div class="middle">
          <h1 class="t1"> <!-- h1 only for 1st slide, h2 for the others -->
            <span>
              <?php echo $slide['content']; ?>
            </span>
          </h1>
          <?php carlo_render('components/cta', $slide['cta']); ?>
        </div>
      </div>
    </div>
    <?php endforeach ?>
  </div>
</section>