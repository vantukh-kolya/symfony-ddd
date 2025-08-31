<?php

namespace App\Catalogue\Contracts\Reservation;

readonly class CatalogueReserveStockRequest
{
    public function __construct(public array $items, array $meta = [])
    {
    }
}
