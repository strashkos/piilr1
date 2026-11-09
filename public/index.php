<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\SubmissionProcessor;

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$studentNumber = 19; // N — номер у журналі
$variantNumber = 61; // варіант: реєстрація доменного імені
$coefficientPercent = 100 + 5 * ($studentNumber % 5); // K = 120

$zoneRates = ['ua' => 650, 'com' => 520, 'org' => 480];

$processor = new SubmissionProcessor($zoneRates, $coefficientPercent);

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
    $result = $processor->process($_POST);
    $errors = $result['errors'];
    $old = $result['old'];

    if ($result['submission'] !== null) {
        // Наступний крок: session + redirect.
        $created = $result['submission']->toArray();
    }
}

require dirname(__DIR__) . '/views/form.php';
