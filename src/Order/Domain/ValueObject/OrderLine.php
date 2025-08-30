<?php

namespace App\Order\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\Money;

class OrderLine
{
    public function __construct(
        private string $productId,
        private string $name,
        private int $quantity,
        private Money $price
    ) {
        if ($quantity <= 0) {
            throw new \LogicException('Quantity > 0 required');
        }
        if ($quantity <= 0) {
            throw new \LogicException('Price > 0 required');
        }
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): Money
    {
        return $this->price;
    }
}
