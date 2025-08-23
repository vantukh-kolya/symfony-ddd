<?php

namespace App\SharedKernel\Contracts\Catalogue;

final class OrderReserveRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
