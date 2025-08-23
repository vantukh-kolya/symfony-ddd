<?php

namespace App\Order\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "order_items")]
class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "items")]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Order $order;

    #[ORM\Column(type: "string", length: 64)]
    private string $product_id;

    #[ORM\Column(type: "integer")]
    private int $quantity;

    #[ORM\Column(type: "integer")]
    private int $price;

    public static function create(Order $order, string $productId, int $quantity, int $price): self
    {
        $orderItem = new self();
        $orderItem->order = $order;
        $orderItem->product_id = $productId;
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
        return $this->product_id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
