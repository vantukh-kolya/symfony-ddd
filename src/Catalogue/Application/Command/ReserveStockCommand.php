<?php

namespace App\Catalogue\Application\Command;

readonly class ReserveStockCommand
{
    public function __construct(public array $items)
    {
    }
}
