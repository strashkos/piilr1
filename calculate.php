<?php

require_once "functions.php";

$domain = $_POST["domain"];
$years = $_POST["years"];
$zone = $_POST["zone"];
$startDate = $_POST["start_date"];
$comment = $_POST["comment"];

$domainError = validateDomain($domain);
$yearsError = validateYears($years);

if ($domainError != "") {
    echo $domainError;
    echo "<br><a href='index.php'>Повернутися</a>";
    exit;
}

if ($yearsError != "") {
    echo $yearsError;
    echo "<br><a href='index.php'>Повернутися</a>";
    exit;
}

$rate = getRate($zone);

$price = $years * $rate;

$discount = 0;

if ($years >= 5) {
    $discount = $price * 0.08;
}

$priceAfterDiscount = $price - $discount;

$checkPrice = 0;

if ($zone == "ua") {
    $checkPrice = 200;
}

$total = $priceAfterDiscount + $checkPrice;

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Результат</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Результат розрахунку</h1>

<p><b>Домен:</b> <?php echo $domain; ?></p>

<p><b>Зона:</b> <?php echo $zone; ?></p>

<p><b>Років:</b> <?php echo $years; ?></p>

<p><b>Ставка:</b> <?php echo $rate; ?> грн/рік</p>

<p><b>Початкова вартість:</b> <?php echo $price; ?> грн</p>

<p><b>Знижка:</b> <?php echo $discount; ?> грн</p>

<p><b>Перевірка даних:</b> <?php echo $checkPrice; ?> грн</p>

<h2>Всього: <?php echo $total; ?> грн</h2>

<a href="index.php">Новий розрахунок</a>

<br><br>

<a href="history.php">Історія</a>

</body>
</html>