
<section class="group/super-section
                py-30
                bg-gray-light">
    <?php
    $elements = carlo_get('elements');
    foreach ($elements as $element): ?>
        <?= carlo_component($element) ?>
    <?php endforeach; ?>
</section>
