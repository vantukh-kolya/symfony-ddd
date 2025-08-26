<?php

namespace App\Order\Application\Port\Dto;

class ReservationCommitRequest
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
