<?php

namespace App\Catalogue\Domain\Entity;

use App\SharedKernel\Domain\ValueObject\Money;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;
    #[ORM\Column(type: "string", length: 255)]
    private string $name;
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    private int $price;
    #[ORM\Column(name: "on_hand", type: "integer", options: ["unsigned" => true])]
    private int $onHand = 0;
    #[ORM\Column(name: "on_hold", type: "integer", options: ["unsigned" => true])]
    private int $onHold = 0;

    public static function create(string $name, Money $price, int $onHand): self
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Name required.');
        }
        $product = new self();
        $product->id = Uuid::v7()->toString();
        $product->name = $name;
        $product->price = $price->toInt();
        $product->onHand = $onHand;

        return $product;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return Money::fromInt($this->price);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOnHand(): int
    {
        return $this->onHand;
    }

    public function getOnHold(): int
    {
        return $this->onHold;
    }

    public function hold(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be > 0.');
        }
        if ($qty > $this->getAvailable()) {
            throw new \LogicException('Not enough stock available to hold.');
        }
        $this->onHold += $qty;
    }

    public function commitReservation(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be > 0.');
        }
        if ($qty > $this->onHold) {
            throw new \LogicException('Cannot commit more than reserved.');
        }
        if ($qty > $this->onHand) {
            throw new \LogicException('Not enough stock on hand.');
        }

        $this->onHold -= $qty;
        $this->onHand -= $qty;
    }

    public function getAvailable(): int
    {
        return $this->onHand - $this->onHold;
    }
}
