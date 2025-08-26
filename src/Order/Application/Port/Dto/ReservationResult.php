<?php

namespace App\Order\Application\Port\Dto;

final class ReservationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $reason = null
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
