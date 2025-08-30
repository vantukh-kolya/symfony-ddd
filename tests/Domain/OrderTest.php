<?php

namespace App\Tests\Domain;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\InvalidOrderStateTransitionException;
use App\Order\Domain\Exception\NonPositiveOrderAmountException;
use App\Order\Domain\Exception\OrderItemsNotReservedException;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function test_create_order_ok(): void
    {
        $order = Order::create(
            'ord-1',
            Money::fromInt(1500),
            $this->line('p1', 'Product 1', 1, 500),
            $this->line('p2', 'Product 2', 2, 500),
        );

        self::assertSame('ord-1', $order->getId());
        self::assertSame(1500, $order->getAmountToPay());

        $items = [...$order->getItems()];
        self::assertCount(2, $items);
        self::assertSame('p1', $items[0]->getProductId());
        self::assertSame(1, $items[0]->getQuantity());
        self::assertTrue(Money::fromInt(500)->equals($items[0]->getPrice()));
    }

    public function test_create_rejects_non_positive_amount(): void
    {
        $this->expectException(NonPositiveOrderAmountException::class);
        Order::create('ord-2', Money::fromInt(0));
    }

    public function test_fulfill_happy_path_requires_reserved(): void
    {
        $order = Order::create('ord-3', Money::fromInt(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setReserved();

        $order->fulfill();

        self::assertTrue($order->isFulfilled());
    }

    public function test_fulfill_throws_when_not_reserved(): void
    {
        $order = Order::create('ord-4', Money::fromInt(500), $this->line('p1', 'Product 1', 1, 500));

        $this->expectException(OrderItemsNotReservedException::class);
        $order->fulfill();
    }

    public function test_fulfill_throws_when_already_fulfilled(): void
    {
        $order = Order::create('ord-5', Money::fromInt(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setReserved();
        $order->fulfill();

        $this->expectException(InvalidOrderStateTransitionException::class);
        $order->fulfill();
    }

    public function test_set_failed_sets_status_failed(): void
    {
        $order = Order::create('ord-6', Money::fromInt(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setFailed();

        self::assertFalse($order->isReserved());
        self::assertFalse($order->isFulfilled());
    }

    private function line(string $pid, string $name, int $qty, int $price): OrderLine
    {
        return new OrderLine($pid, $name, $qty, Money::fromInt($price));
    }

}
