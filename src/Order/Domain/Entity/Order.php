<?php

namespace App\Order\Domain\Entity;

use App\Order\Domain\Enum\OrderStatus;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\ValueObject\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private string $id;
    #[ORM\Column(type: "integer")]
    private int $amount_to_pay;
    #[ORM\Column(type: 'string', length: 32)]
    private string $status;
    #[ORM\Column(type: "datetime")]
    private \DateTime $created_at;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderItem::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $items;

    public static function create(Money $amountToPay, OrderLine ...$lines): self
    {
        $amountToPayValue = $amountToPay->toInt();
        if ($amountToPayValue <= 0) {
            throw new \InvalidArgumentException("Order amount must be greater than zero.");
        }

        $order = new self();
        $order->id = Uuid::v7();
        $order->amount_to_pay = $amountToPayValue;
        $order->status = OrderStatus::PENDING->value;
        $order->created_at = new \DateTime();

        foreach ($lines as $l) {
            $order->items->add(
                OrderItem::create($order, $l->productId(), $l->quantity(), $l->price()->toInt())
            );
        }

        return $order;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmountToPay(): int
    {
        return $this->amount_to_pay;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setReserved(): void
    {
        $this->status = OrderStatus::RESERVED->value;
    }

    public function setReservationFailed(): void
    {
        $this->status = OrderStatus::RESERVATION_FAILED->value;
    }
}
