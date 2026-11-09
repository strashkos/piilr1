<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$zoneRates = ['ua' => 650, 'com' => 520, 'org' => 480];
$allowedZones = array_keys($zoneRates);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$errors = [];
$old = [
    'domain' => '',
    'years' => '',
    'zone' => '',
    'start_date' => '',
    'comment' => '',
];
$created = null;

if ($method === 'POST') {
    // Домен: обов'язковий, 3–100 символів, має крапку, без пробілів.
    $old['domain'] = trim((string) ($_POST['domain'] ?? ''));
    $domainLength = mb_strlen($old['domain'], 'UTF-8');
    if ($old['domain'] === '') {
        $errors['domain'] = 'Поле є обов’язковим.';
    } elseif ($domainLength < 3 || $domainLength > 100) {
        $errors['domain'] = 'Домен повинен мати від 3 до 100 символів.';
    } elseif (preg_match('/\s/u', $old['domain']) === 1) {
        $errors['domain'] = 'Домен не повинен містити пробілів.';
    } elseif (!str_contains($old['domain'], '.')) {
        $errors['domain'] = 'Домен повинен містити крапку.';
    }

    // Строк у роках: ціле число 1–10.
    $old['years'] = trim((string) ($_POST['years'] ?? ''));
    $years = filter_var($old['years'], FILTER_VALIDATE_INT);
    if ($old['years'] === '') {
        $errors['years'] = 'Поле є обов’язковим.';
    } elseif ($years === false) {
        $errors['years'] = 'Введіть ціле число.';
    } elseif ($years < 1 || $years > 10) {
        $errors['years'] = 'Строк повинен бути від 1 до 10 років.';
    }

    // Доменна зона: тільки з серверного whitelist.
    $old['zone'] = trim((string) ($_POST['zone'] ?? ''));
    if (!in_array($old['zone'], $allowedZones, true)) {
        $errors['zone'] = 'Оберіть допустиму доменну зону.';
    }

    // Дата початку: реальна календарна дата.
    $old['start_date'] = trim((string) ($_POST['start_date'] ?? ''));
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $old['start_date']);
    if ($old['start_date'] === '') {
        $errors['start_date'] = 'Поле є обов’язковим.';
    } elseif ($date === false || $date->format('Y-m-d') !== $old['start_date']) {
        $errors['start_date'] = 'Введіть коректну дату.';
    }

    // Коментар: необов'язковий, до 500 символів.
    $old['comment'] = trim((string) ($_POST['comment'] ?? ''));
    if (mb_strlen($old['comment'], 'UTF-8') > 500) {
        $errors['comment'] = 'Коментар не повинен перевищувати 500 символів.';
    }

    if ($errors === []) {
        $cost = $years * $zoneRates[$old['zone']];
        if ($years >= 5) {
            $cost *= 0.92;
        }
        if ($old['zone'] === 'ua') {
            $cost += 200;
        }

        $created = $old;
        $created['cost'] = (int) round($cost);
    }
}

require dirname(__DIR__) . '/views/form.php';
