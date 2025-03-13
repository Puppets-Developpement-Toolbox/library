<?php
$elements = carlo_get('elements');
if(!empty($elements)): ?>
<section class="group/super-section
                py-30
                bg-gray-light">
    <?php
        foreach ($elements as $element): ?>
            <?= carlo_component($element) ?>
        <?php endforeach; ?>
</section>
<?php endif; ?>
