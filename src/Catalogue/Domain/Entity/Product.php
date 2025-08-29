<?php

namespace App\Catalogue\Domain\Entity;

use App\Catalogue\Domain\Exception\EmptyProductNameException;
use App\Catalogue\Domain\Exception\InsufficientStockException;
use App\Catalogue\Domain\Exception\NonPositiveQuantityException;
use App\Catalogue\Domain\Exception\OverCommitReservationException;
use App\SharedKernel\Domain\ValueObject\Money;

class Product
{

    private string $id;
    private string $name;
    private int $price;
    private int $onHand = 0;
    private int $onHold = 0;

    public static function create(string $id, string $name, Money $price, int $onHand): self
    {
        if ($name === '') {
            throw new EmptyProductNameException('Name required.');
        }
        $self = new self();
        $self->id = $id;
        $self->name = $name;
        $self->price = $price->toInt();
        $self->onHand = $onHand;
        return $self;
    }

    public function hold(int $qty): void
    {
        if ($qty <= 0) {
            throw new NonPositiveQuantityException('Quantity must be > 0.');
        }
        if ($qty > $this->getAvailable()) {
            throw new InsufficientStockException('Not enough stock available to hold.');
        }
        $this->onHold += $qty;
    }

    public function commitReservation(int $qty): void
    {
        if ($qty <= 0) {
            throw new NonPositiveQuantityException('Quantity must be > 0.');
        }
        if ($qty > $this->onHold) {
            throw new OverCommitReservationException('Cannot commit more than reserved.');
        }

        $this->onHold -= $qty;
        $this->onHand -= $qty;
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


    public function getAvailable(): int
    {
        return $this->onHand - $this->onHold;
    }
}
