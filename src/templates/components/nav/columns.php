<?php $items = carlo_get('items'); ?>
<div style="--count-columns: <?= count($items) ?>"
    class="flex flex-col gap-8
            laptop:grid laptop:grid-cols-[repeat(var(--count-columns),minmax(0,1fr))] laptop:gap-[10%]">


    <?php foreach($items as $item): ?>

        <section class="flex flex-col gap-4">
            <header class="font-semibold uppercase text-secondary">
                <?php if(!in_array($item['href'], ['', '#'])): ?><a href="<?=$item['href']; ?>" /><?php endif; ?>
                    <?=$item['name'] ?>
                <?php if(!in_array($item['href'], ['', '#'])): ?></a><?php endif; ?>
            </header>
            <ul class="[ fade-not-hovered ] flex flex-col gap-4">
                <?php if(!empty($item['children'])): ?>
                    <?php foreach($item['children'] as $child) : ?>
                        <li><a href="<?=$child['href'] ?>"><?=$child['name'] ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>

    <?php endforeach; ?>
