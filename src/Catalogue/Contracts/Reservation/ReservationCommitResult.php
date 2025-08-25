<?php

namespace App\Catalogue\Contracts\Reservation;

readonly class ReservationCommitResult
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
