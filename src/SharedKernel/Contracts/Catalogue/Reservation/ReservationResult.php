<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

readonly class ReservationResult
{
    private function __construct(public bool $success, public ?string $reason = null)
    {
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
