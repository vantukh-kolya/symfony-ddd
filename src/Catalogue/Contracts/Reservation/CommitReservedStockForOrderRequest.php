<?php

namespace App\Catalogue\Contracts\Reservation;

final class CommitReservedStockForOrderRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
