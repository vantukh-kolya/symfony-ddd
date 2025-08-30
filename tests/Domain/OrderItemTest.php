<?php

namespace App\Tests\Domain;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class OrderItemTest extends TestCase
{
    public function test_order_item_creation_and_getters(): void
    {
        $order = Order::create(
            'ord-7',
            Money::fromInt(1000),
            new OrderLine('p1', 'Product 1', 1, Money::fromInt(1000)),
        );

        $items = [...$order->getItems()];
        self::assertCount(1, $items);

        $item = $items[0];
        self::assertSame('p1', $item->getProductId());
        self::assertSame(1, $item->getQuantity());
        self::assertTrue(Money::fromInt(1000)->equals($item->getPrice()));

        $direct = OrderItem::create($order, 'p2', 'Product 2', 2, 500);
        self::assertSame('p2', $direct->getProductId());
        self::assertSame(2, $direct->getQuantity());
        self::assertTrue(Money::fromInt(500)->equals($direct->getPrice()));
    }
}
