<?php

namespace App\Catalogue\Application\Command;

readonly class ReserveStockForOrderCommand
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
