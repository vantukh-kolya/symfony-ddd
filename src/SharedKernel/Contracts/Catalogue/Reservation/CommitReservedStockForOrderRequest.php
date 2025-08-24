<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

final class CommitReservedStockForOrderRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
