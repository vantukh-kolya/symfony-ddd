<?php

namespace App\Order\Application\Dto;

class OrderReservationPayload
{
    public function __construct(public readonly string $orderId, public readonly array $items)
    {
    }
}
