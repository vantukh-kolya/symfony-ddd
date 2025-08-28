<?php

namespace App\Order\Domain\Entity;

use App\Order\Domain\Enum\OrderStatus;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\ValueObject\Money;

class Order
{
    private string $id;
    private int $amountToPay;
    private string $status;
    private \DateTime $createdAt;
    private ?\DateTime $fulfilledAt = null;
    private iterable $items = [];

    public static function create(string $id, Money $amountToPay, OrderLine ...$lines): self
    {
        $amountToPayValue = $amountToPay->toInt();
        if ($amountToPayValue <= 0) {
            throw new \InvalidArgumentException("Order amount must be greater than zero.");
        }

        $order = new self();
        $order->id = $id;
        $order->amountToPay = $amountToPayValue;
        $order->status = OrderStatus::PENDING->value;
        $order->createdAt = new \DateTime();

        foreach ($lines as $l) {
            $order->items[] = OrderItem::create($order, $l->productId(), $l->quantity(), $l->price()->toInt());
        }

        return $order;
    }

    public function fulfill(): void
    {
        $this->status = OrderStatus::FULFILLED->value;
        $this->fulfilledAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmountToPay(): int
    {
        return $this->amountToPay;
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function setReserved(): void
    {
        $this->status = OrderStatus::RESERVED->value;
    }

    public function setFailed(): void
    {
        $this->status = OrderStatus::FAILED->value;
    }


    public function isFulfilled(): bool
    {
        return $this->status === OrderStatus::FULFILLED->value;
    }

    public function isReserved(): bool
    {
        return $this->status === OrderStatus::RESERVED->value;
    }
}
