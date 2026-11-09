<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;

/**
 * Нормалізує, перевіряє вхідні дані та обчислює вартість реєстрації домену.
 */
final class SubmissionProcessor
{
    private const MIN_YEARS = 1;
    private const MAX_YEARS = 10;
    private const DISCOUNT_FROM_YEARS = 5;
    private const DISCOUNT_PERCENT = 8;
    private const UA_CHECK_FEE = 200;
    private const COMMENT_MAX_LENGTH = 500;

    /**
     * @param array<string, int> $zoneRates доменна зона => ставка, грн/рік
     * @param int $coefficientPercent коефіцієнт K у відсотках
     */
    public function __construct(
        private readonly array $zoneRates,
        private readonly int $coefficientPercent,
    ) {
    }

    /**
     * @param array<string, mixed> $input
     * @return array{submission: ?Submission, errors: array<string, string>, old: array<string, string>}
     */
    public function process(array $input): array
    {
        $old = [
            'domain' => trim((string) ($input['domain'] ?? '')),
            'years' => trim((string) ($input['years'] ?? '')),
            'zone' => trim((string) ($input['zone'] ?? '')),
            'start_date' => trim((string) ($input['start_date'] ?? '')),
            'comment' => trim((string) ($input['comment'] ?? '')),
        ];
        $errors = [];

        $domainError = $this->validateDomain($old['domain']);
        if ($domainError !== null) {
            $errors['domain'] = $domainError;
        }

        $years = filter_var($old['years'], FILTER_VALIDATE_INT);
        if ($old['years'] === '') {
            $errors['years'] = 'Поле є обов’язковим.';
        } elseif ($years === false) {
            $errors['years'] = 'Введіть ціле число.';
        } elseif ($years < self::MIN_YEARS || $years > self::MAX_YEARS) {
            $errors['years'] = 'Строк повинен бути від 1 до 10 років.';
        }

        if (!array_key_exists($old['zone'], $this->zoneRates)) {
            $errors['zone'] = 'Оберіть допустиму доменну зону.';
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $old['start_date']);
        if ($old['start_date'] === '') {
            $errors['start_date'] = 'Поле є обов’язковим.';
        } elseif ($date === false || $date->format('Y-m-d') !== $old['start_date']) {
            $errors['start_date'] = 'Введіть коректну дату.';
        }

        if (mb_strlen($old['comment'], 'UTF-8') > self::COMMENT_MAX_LENGTH) {
            $errors['comment'] = 'Коментар не повинен перевищувати 500 символів.';
        }

        if ($errors !== []) {
            return ['submission' => null, 'errors' => $errors, 'old' => $old];
        }

        /** @var int $years */
        $submission = new Submission(
            domain: $old['domain'],
            years: $years,
            zone: $old['zone'],
            startDate: $old['start_date'],
            comment: $old['comment'] === '' ? null : $old['comment'],
            cost: $this->calculateCost($years, $old['zone']),
        );

        return ['submission' => $submission, 'errors' => [], 'old' => $old];
    }

    /**
     * Вартість = роки × ставка; від 5 років знижка 8%;
     * зона ua додає перевірку даних 200 грн; потім K, округлення до цілого.
     */
    public function calculateCost(int $years, string $zone): int
    {
        $cost = $years * $this->zoneRates[$zone];

        if ($years >= self::DISCOUNT_FROM_YEARS) {
            $cost = $cost * (100 - self::DISCOUNT_PERCENT) / 100;
        }

        if ($zone === 'ua') {
            $cost += self::UA_CHECK_FEE;
        }

        return (int) round($cost * $this->coefficientPercent / 100);
    }

    private function validateDomain(string $domain): ?string
    {
        $length = mb_strlen($domain, 'UTF-8');

        return match (true) {
            $domain === '' => 'Поле є обов’язковим.',
            $length < 3 || $length > 100 => 'Домен повинен мати від 3 до 100 символів.',
            preg_match('/\s/u', $domain) === 1 => 'Домен не повинен містити пробілів.',
            !str_contains($domain, '.') => 'Домен повинен містити крапку.',
            default => null,
        };
    }
}
