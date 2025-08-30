<?php

namespace App\Order\Application\Command;

readonly class CreateOrderCommand
{
    public function __construct(
        public string $id,
        public int $amountToPay,
        public array $products,
    ) {
    }
}
