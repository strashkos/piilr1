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
$historyLimit = 3 + ($studentNumber % 4); // L = 6

$zoneRates = ['ua' => 650, 'com' => 520, 'org' => 480];
$allowedZones = array_keys($zoneRates);

session_start();
header("X-Student-Variant: {$studentNumber}-{$variantNumber}");

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

if ($method === 'POST') {
    $result = $processor->process($_POST);
    $errors = $result['errors'];
    $old = $result['old'];

    if ($result['submission'] !== null) {
        $history = $_SESSION['submissions'] ?? [];
        $history[] = $result['submission']->toArray();
        $_SESSION['submissions'] = array_slice($history, -$historyLimit);

        header('Location: /?created=1', true, 303);
        exit;
    }
}

$created = ($_GET['created'] ?? '') === '1';

// GET-фільтр за доменною зоною змінює лише показ, session не змінюється.
$history = $_SESSION['submissions'] ?? [];
$zoneFilter = trim((string) ($_GET['zone'] ?? ''));
if ($zoneFilter !== '' && in_array($zoneFilter, $allowedZones, true)) {
    $history = array_values(array_filter(
        $history,
        static fn (array $row): bool => $row['zone'] === $zoneFilter,
    ));
} else {
    $zoneFilter = '';
}

require dirname(__DIR__) . '/views/form.php';
