<?php

namespace App\Tests\Application;

use App\Catalogue\Application\Command\Handler\ReserveStockForOrderCommandHandler;
use App\Catalogue\Application\Command\ReserveStockForOrderCommand;
use App\Catalogue\Domain\Entity\Product;
use App\SharedKernel\Domain\ValueObject\Money;
use App\Tests\Support\InMemoryProductRepository;
use App\Tests\Support\InMemoryTransactionRunner;
use PHPUnit\Framework\TestCase;

final class ReserveStockForOrderCommandHandlerTest extends TestCase
{
    public function test_reserves_stock_for_all_items(): void
    {
        $repo = new InMemoryProductRepository([
            'p1' => Product::create('p1', 'A', Money::fromInt(100), 5),
            'p2' => Product::create('p2', 'B', Money::fromInt(200), 3),
        ]);
        $transactionRunner = new InMemoryTransactionRunner();

        $handler = new ReserveStockForOrderCommandHandler($repo, $transactionRunner);

        ($handler)(new ReserveStockForOrderCommand('ord-1', [
            ['product_id' => 'p1', 'quantity' => 2],
            ['product_id' => 'p2', 'quantity' => 1],
        ]));

        self::assertSame(2, $repo->get('p1')->getOnHold());
        self::assertSame(1, $repo->get('p2')->getOnHold());
    }

    public function test_throws_on_invalid_item_shape(): void
    {
        $repo = new InMemoryProductRepository([]);
        $transactionRunner = new InMemoryTransactionRunner();
        $handler = new ReserveStockForOrderCommandHandler($repo, $transactionRunner);

        $this->expectException(\DomainException::class);
        ($handler)(new ReserveStockForOrderCommand('ord-1', [
            ['product_id' => 'p1'],
        ]));
    }

    public function test_throws_on_unknown_product(): void
    {
        $repo = new InMemoryProductRepository([]);
        $tx = new InMemoryTransactionRunner();
        $handler = new ReserveStockForOrderCommandHandler($repo, $tx);

        $this->expectException(\DomainException::class);
        ($handler)(new ReserveStockForOrderCommand('ord-1', [
            ['product_id' => 'unknown', 'quantity' => 1],
        ]));
    }
}
