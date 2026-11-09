<?php
/**
 * @var array<string, string> $old
 * @var array<string, string> $errors
 * @var array<string, int> $zoneRates
 * @var array<string, mixed>|null $created
 */
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація доменного імені</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<h1>Реєстрація доменного імені</h1>

<?php if ($created !== null): ?>
    <p class="success">
        Заявку прийнято: <?= e($created['domain']) ?>, вартість <?= e($created['cost']) ?> грн.
    </p>
<?php endif; ?>

<form method="post" action="/">
    <label>
        Домен
        <input name="domain" value="<?= e($old['domain']) ?>" required minlength="3" maxlength="100">
    </label>
    <?php if (isset($errors['domain'])): ?>
        <p class="error"><?= e($errors['domain']) ?></p>
    <?php endif; ?>

    <label>
        Строк у роках
        <input type="number" name="years" value="<?= e($old['years']) ?>" required min="1" max="10" step="1">
    </label>
    <?php if (isset($errors['years'])): ?>
        <p class="error"><?= e($errors['years']) ?></p>
    <?php endif; ?>

    <label>
        Доменна зона
        <select name="zone" required>
            <option value="">— оберіть —</option>
            <?php foreach ($zoneRates as $zone => $rate): ?>
                <option value="<?= e($zone) ?>" <?= $old['zone'] === $zone ? 'selected' : '' ?>>
                    .<?= e($zone) ?> — <?= e($rate) ?> грн/рік
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['zone'])): ?>
        <p class="error"><?= e($errors['zone']) ?></p>
    <?php endif; ?>

    <label>
        Дата початку
        <input type="date" name="start_date" value="<?= e($old['start_date']) ?>" required>
    </label>
    <?php if (isset($errors['start_date'])): ?>
        <p class="error"><?= e($errors['start_date']) ?></p>
    <?php endif; ?>

    <label>
        Коментар (необов’язково)
        <textarea name="comment" maxlength="500"><?= e($old['comment']) ?></textarea>
    </label>
    <?php if (isset($errors['comment'])): ?>
        <p class="error"><?= e($errors['comment']) ?></p>
    <?php endif; ?>

    <button type="submit">Зберегти заявку</button>
</form>

</body>
</html>
