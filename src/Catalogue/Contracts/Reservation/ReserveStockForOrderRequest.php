<?php

namespace App\Catalogue\Contracts\Reservation;

final class ReserveStockForOrderRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
