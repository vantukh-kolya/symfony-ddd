<?php

namespace App\Catalogue\Application\Command;

class CommitReservedStockForOrderCommand
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
