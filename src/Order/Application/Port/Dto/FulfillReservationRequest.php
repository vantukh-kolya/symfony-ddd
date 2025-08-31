<?php

namespace App\Order\Application\Port\Dto;

readonly class FulfillReservationRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
