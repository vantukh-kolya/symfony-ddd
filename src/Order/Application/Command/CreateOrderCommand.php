<?php

namespace App\Order\Application\Command;

readonly class CreateOrderCommand
{
    public function __construct(
        public string $id,
        public int $amountToPay,
        /** @var array<int, array{product_id:string, quantity:int, price:int}> */
        public array $products,
    ) {
    }
}
