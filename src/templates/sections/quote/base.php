
<section class="[ section ]
                bg-transparent
                laptop:grid laptop:grid-cols-12">
    <?php if(carlo_get("titre")): ?>
        <p class="[ h4 ] laptop:col-span-10 laptop:col-start-2 ">
            <?= str_replace(' >', '</em>', str_replace('< ', '<em>', carlo_get("titre"))); ?>
        </p>
    <?php endif; ?>
    <?php carlo_component('quote') ?>
</section>
