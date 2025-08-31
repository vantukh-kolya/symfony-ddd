<?php

namespace App\Tests\Application\Command;

use App\Catalogue\Application\Command\FulfillStockReservationCommand;
use App\Catalogue\Application\Command\Handler\FulfillStockReservationCommandHandler;
use App\Catalogue\Domain\Entity\Product;
use App\SharedKernel\Domain\ValueObject\Money;
use App\Tests\Support\InMemoryProductRepository;
use App\Tests\Support\InMemoryTransactionRunner;
use PHPUnit\Framework\TestCase;

final class FulfillStockReservationCommandHandlerTest extends TestCase
{
    public function test_commits_reserved_stock_for_all_items(): void
    {
        $p1 = Product::create('p1', 'A', Money::fromMinor(100), 5);
        $p2 = Product::create('p2', 'B', Money::fromMinor(200), 3);

        $p1->hold(2);
        $p2->hold(1);

        $repo = new InMemoryProductRepository(['p1' => $p1, 'p2' => $p2]);
        $transactionRunner = new InMemoryTransactionRunner();

        $handler = new FulfillStockReservationCommandHandler($repo, $transactionRunner);

        ($handler)(new FulfillStockReservationCommand([
            ['product_id' => 'p1', 'quantity' => 2],
            ['product_id' => 'p2', 'quantity' => 1],
        ]));

        self::assertSame(0, $repo->get('p1')->getOnHold());
        self::assertSame(3, $repo->get('p1')->getOnHand());

        self::assertSame(0, $repo->get('p2')->getOnHold());
        self::assertSame(2, $repo->get('p2')->getOnHand());
    }

    public function test_throws_on_unknown_product(): void
    {
        $repo = new InMemoryProductRepository([]);
        $transactionRunner = new InMemoryTransactionRunner();
        $handler = new FulfillStockReservationCommandHandler($repo, $transactionRunner);

        $this->expectException(\DomainException::class);
        ($handler)(new FulfillStockReservationCommand([
            ['product_id' => 'unknown', 'quantity' => 1],
        ]));
    }

    public function test_throws_when_committing_more_than_reserved(): void
    {
        $p1 = Product::create('p1', 'A', Money::fromMinor(100), 5);
        $p1->hold(1);

        $repo = new InMemoryProductRepository(['p1' => $p1]);
        $transactionRunner = new InMemoryTransactionRunner();
        $handler = new FulfillStockReservationCommandHandler($repo, $transactionRunner);

        $this->expectException(\DomainException::class);
        ($handler)(new FulfillStockReservationCommand([
            ['product_id' => 'p1', 'quantity' => 2],
        ]));
    }
}
