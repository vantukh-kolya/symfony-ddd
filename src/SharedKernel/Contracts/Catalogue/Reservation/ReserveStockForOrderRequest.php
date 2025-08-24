<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

final class ReserveStockForOrderRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
