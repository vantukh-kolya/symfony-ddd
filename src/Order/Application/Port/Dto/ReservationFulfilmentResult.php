<?php

namespace App\Order\Application\Port\Dto;

readonly class ReservationFulfilmentResult
{
    public function __construct(
        public bool $success,
        public ?string $reason = null
    ) {
    }

    public static function ok(): self
    {
        return new self(true);
    }

    public static function fail(string $reason): self
    {
        return new self(false, $reason);
    }
}
