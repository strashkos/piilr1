<?php
/**
 * @var array<int, array<string, mixed>> $history
 * @var array<string, int> $zoneRates
 * @var string $zoneFilter
 * @var int $historyLimit
 */
?>
<h2>Історія заявок (останні <?= e($historyLimit) ?>)</h2>

<p class="filters">
    Фільтр за зоною:
    <?php foreach (array_keys($zoneRates) as $zone): ?>
        <?php if ($zoneFilter === $zone): ?>
            <strong>.<?= e($zone) ?></strong>
        <?php else: ?>
            <a href="/?zone=<?= e(urlencode($zone)) ?>">.<?= e($zone) ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
    <a href="/">Показати всі</a>
</p>

<?php if ($history === []): ?>
    <p>Заявок поки немає.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Домен</th>
            <th>Роки</th>
            <th>Зона</th>
            <th>Дата початку</th>
            <th>Коментар</th>
            <th>Вартість, грн</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($history as $row): ?>
            <tr>
                <td><?= e($row['domain']) ?></td>
                <td><?= e($row['years']) ?></td>
                <td>.<?= e($row['zone']) ?></td>
                <td><?= e($row['startDate']) ?></td>
                <td><?= e($row['comment'] ?? '—') ?></td>
                <td><?= e($row['cost']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
