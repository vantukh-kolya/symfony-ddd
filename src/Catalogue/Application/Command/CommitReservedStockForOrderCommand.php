<?php

namespace App\Catalogue\Application\Command;

readonly class CommitReservedStockForOrderCommand
{
    public function __construct(public string $orderId, public array $items)
    {
    }
}
