<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

final class OrderReserveRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
