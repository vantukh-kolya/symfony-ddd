<?php

namespace App\Order\Domain\Entity;

use App\SharedKernel\Domain\ValueObject\Money;

class OrderItem
{
    private int $id;
    private Order $order;
    private string $productId;
    private string $name;
    private int $quantity;
    private int $price;

    public static function create(Order $order, string $productId, string $name, int $quantity, int $price): self
    {
        $orderItem = new self();
        $orderItem->order = $order;
        $orderItem->productId = $productId;
        $orderItem->name = $name;
        $orderItem->quantity = $quantity;
        $orderItem->price = $price;

        return $orderItem;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPrice(): Money
    {
        return Money::fromInt($this->price);
    }
}
