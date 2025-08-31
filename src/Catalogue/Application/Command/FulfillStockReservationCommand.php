<?php

namespace App\Catalogue\Application\Command;

readonly class FulfillStockReservationCommand
{
    public function __construct(public array $items)
    {
    }
}
