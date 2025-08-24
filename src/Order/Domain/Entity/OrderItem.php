<?php

namespace App\Order\Domain\Entity;

class OrderItem
{
    private int $id;
    private Order $order;
    private string $productId;
    private int $quantity;
    private int $price;

    public static function create(Order $order, string $productId, int $quantity, int $price): self
    {
        $orderItem = new self();
        $orderItem->order = $order;
        $orderItem->productId = $productId;
        $orderItem->quantity = $quantity;
        $orderItem->price = $price;

        return $orderItem;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
