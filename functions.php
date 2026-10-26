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