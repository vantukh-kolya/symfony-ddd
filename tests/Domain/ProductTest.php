<?php

namespace App\Tests\Domain;

use App\Catalogue\Domain\Entity\Product;
use App\Catalogue\Domain\Exception\EmptyProductNameException;
use App\Catalogue\Domain\Exception\InsufficientStockException;
use App\Catalogue\Domain\Exception\NonPositiveQuantityException;
use App\Catalogue\Domain\Exception\OverCommitReservationException;
use App\SharedKernel\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function test_create_product_ok(): void
    {
        $p = Product::create('p1', 'Apple', Money::fromMinor(199), 10);

        self::assertSame('p1', $p->getId());
        self::assertSame('Apple', $p->getName());
        self::assertSame(199, $p->getPrice()->toMinor());
        self::assertSame(10, $p->getOnHand());
        self::assertSame(0, $p->getOnHold());
        self::assertSame(10, $p->getAvailable());
    }

    public function test_create_rejects_empty_name(): void
    {
        $this->expectException(EmptyProductNameException::class);
        Product::create('p1', '', Money::fromMinor(100), 5);
    }

    public function test_hold_increases_on_hold_and_reduces_available(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);

        $p->hold(3);

        self::assertSame(5, $p->getOnHand());
        self::assertSame(3, $p->getOnHold());
        self::assertSame(2, $p->getAvailable());
    }

    public function test_hold_rejects_non_positive_quantity(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);

        $this->expectException(NonPositiveQuantityException::class);
        $p->hold(0);
    }

    public function test_hold_rejects_when_insufficient_available(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);
        $p->hold(4);

        $this->expectException(InsufficientStockException::class);
        $p->hold(2);
    }

    public function test_commit_reservation_happy_path(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);
        $p->hold(3);

        $p->commitReservation(2);

        self::assertSame(3, $p->getOnHand());
        self::assertSame(1, $p->getOnHold());
        self::assertSame(2, $p->getAvailable());
    }

    public function test_commit_rejects_non_positive_quantity(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);

        $this->expectException(NonPositiveQuantityException::class);
        $p->commitReservation(0);
    }

    public function test_commit_cannot_exceed_on_hold(): void
    {
        $p = Product::create('p1', 'A', Money::fromMinor(100), 5);
        $p->hold(2);

        $this->expectException(OverCommitReservationException::class);
        $p->commitReservation(3);
    }

}
