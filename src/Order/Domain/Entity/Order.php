<?php

namespace App\Order\Domain\Entity;

use App\Order\Domain\Enum\OrderStatus;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\ValueObject\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Order
{
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    private string $id;
    private int $amountToPay;
    private string $status;
    private \DateTime $createdAt;
    private ?\DateTime $fulfilledAt = null;
    private Collection $items;

    public static function create(Money $amountToPay, OrderLine ...$lines): self
    {
        $amountToPayValue = $amountToPay->toInt();
        if ($amountToPayValue <= 0) {
            throw new \InvalidArgumentException("Order amount must be greater than zero.");
        }

        $order = new self();
        $order->id = Uuid::v7();
        $order->amountToPay = $amountToPayValue;
        $order->status = OrderStatus::PENDING->value;
        $order->createdAt = new \DateTime();

        foreach ($lines as $l) {
            $order->items->add(
                OrderItem::create($order, $l->productId(), $l->quantity(), $l->price()->toInt())
            );
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
