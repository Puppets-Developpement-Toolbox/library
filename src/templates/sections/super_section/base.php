


<section class="group/super-section
                py-30
                bg-gray-light">
    <?php foreach (carlo_get("elements") as $element): ?>
        <?= carlo_component($element) ?>
    <?php endforeach; ?>
</section>
