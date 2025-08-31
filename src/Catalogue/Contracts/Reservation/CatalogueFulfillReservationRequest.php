<?php

namespace App\Catalogue\Contracts\Reservation;

readonly class CatalogueFulfillReservationRequest
{
    public function __construct(public array $items, array $meta = [])
    {
    }
}
