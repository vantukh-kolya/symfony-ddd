<?php

namespace App\SharedKernel\Contracts\Catalogue\Stock;

class OrderReservationCommitRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
