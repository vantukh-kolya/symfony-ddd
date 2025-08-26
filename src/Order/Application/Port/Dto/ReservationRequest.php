<?php

namespace App\Order\Application\Port\Dto;

class ReservationRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
