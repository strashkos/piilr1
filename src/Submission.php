<?php

declare(strict_types=1);

namespace App;

/**
 * Валідна заявка на реєстрацію доменного імені.
 */
final readonly class Submission
{
    public function __construct(
        public string $domain,
        public int $years,
        public string $zone,
        public string $startDate,
        public ?string $comment,
        public int $cost,
    ) {
    }

    /**
     * @return array{domain: string, years: int, zone: string, startDate: string, comment: ?string, cost: int}
     */
    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'years' => $this->years,
            'zone' => $this->zone,
            'startDate' => $this->startDate,
            'comment' => $this->comment,
            'cost' => $this->cost,
        ];
    }
}
