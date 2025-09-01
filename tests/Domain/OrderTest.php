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
        $lines = [
            ['p1', 'Product 1', 1, 500],
            ['p2', 'Product 2', 2, 500],
        ];

        $order = Order::create(
            'ord-1',
            Money::fromMinor(1500),
            ...array_map(fn($l) => $this->line(...$l), $lines),
        );

        self::assertSame('ord-1', $order->getId());
        self::assertSame(1500, $order->getAmountToPay());

        $items = [...$order->getItems()];
        self::assertCount(count($lines), $items);

        foreach ($lines as $i => [$pid, , $qty, $price]) {
            $item = $items[$i];
            self::assertSame($pid, $item->getProductId());
            self::assertSame($qty, $item->getQuantity());
            self::assertTrue(Money::fromMinor($price)->equals($item->getPrice()));
        }
    }

    public function test_create_rejects_non_positive_amount(): void
    {
        $this->expectException(NonPositiveOrderAmountException::class);
        Order::create('ord-2', Money::fromMinor(0));
    }

    public function test_fulfill_happy_path_requires_reserved(): void
    {
        $order = Order::create('ord-3', Money::fromMinor(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setReserved();

        $order->fulfill();

        self::assertTrue($order->isFulfilled());
    }

    public function test_fulfill_throws_when_not_reserved(): void
    {
        $order = Order::create('ord-4', Money::fromMinor(500), $this->line('p1', 'Product 1', 1, 500));

        $this->expectException(OrderItemsNotReservedException::class);
        $order->fulfill();
    }

    public function test_fulfill_throws_when_already_fulfilled(): void
    {
        $order = Order::create('ord-5', Money::fromMinor(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setReserved();
        $order->fulfill();

        $this->expectException(InvalidOrderStateTransitionException::class);
        $order->fulfill();
    }

    public function test_set_failed_sets_status_failed(): void
    {
        $order = Order::create('ord-6', Money::fromMinor(500), $this->line('p1', 'Product 1', 1, 500));
        $order->setFailed();

        self::assertFalse($order->isReserved());
        self::assertFalse($order->isFulfilled());
    }

    private function line(string $pid, string $name, int $qty, int $price): OrderLine
    {
        return new OrderLine($pid, $name, $qty, Money::fromMinor($price));
    }

}
