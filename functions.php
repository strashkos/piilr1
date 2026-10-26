<?php
function getRate($zone)
{
    if ($zone == "ua") {
        return 650;
    }

    if ($zone == "com") {
        return 520;
    }

    if ($zone == "org") {
        return 480;
    }

    return 0;
}
function validateDomain($domain)
{
    if (strlen($domain) < 3) {
        return "Домен має містити мінімум 3 символи";
    }

    if (strlen($domain) > 100) {
        return "Домен має містити максимум 100 символів";
    }

    if (strpos($domain, ".") === false) {
        return "Домен має містити крапку";
    }

    if (strpos($domain, " ") !== false) {
        return "Домен не може містити пробілів";
    }

    return "";
}


function validateYears($years)
{
    if ($years < 1 || $years > 10) {
        return "Кількість років має бути від 1 до 10";
    }

    return "";
}
function calculatePrice($years, $zone)
{
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

    return $total;
}
function saveHistory($domain, $years, $zone, $startDate, $comment, $total)
{
    $file = "data/history.json";

    $history = json_decode(file_get_contents($file), true);

    $item = array(
        "domain" => $domain,
        "years" => $years,
        "zone" => $zone,
        "start_date" => $startDate,
        "comment" => $comment,
        "total" => $total
    );

    $history[] = $item;

    file_put_contents(
        $file,
        json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}