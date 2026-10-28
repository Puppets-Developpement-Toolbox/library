<section class="[ section ]">
  <ul>
    <?php foreach (carlo_get('hours') as $row): ?>
      <li><?= esc_html($row['day']) ?> : <?= esc_html($row['time']) ?></li>
    <?php endforeach ?>
  </ul>
  <?php if ($note = carlo_get('note')): ?>
    <p><?= esc_html($note) ?></p>
  <?php endif ?>
</section>
