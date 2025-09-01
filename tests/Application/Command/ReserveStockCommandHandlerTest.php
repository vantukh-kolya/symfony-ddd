<?php

namespace App\Tests\Application\Command;

use App\Catalogue\Application\Command\CommandValidatorInterface;
use App\Catalogue\Application\Command\Handler\ReserveStockCommandHandler;
use App\Catalogue\Application\Command\ReserveStockCommand;
use App\Catalogue\Domain\Entity\Product;
use App\SharedKernel\Domain\ValueObject\Money;
use App\Tests\Support\InMemoryProductRepository;
use App\Tests\Support\InMemoryTransactionRunner;
use PHPUnit\Framework\TestCase;

final class ReserveStockCommandHandlerTest extends TestCase
{
    public function test_reserves_stock_for_all_items(): void
    {
        $repo = new InMemoryProductRepository([
            'p1' => Product::create('p1', 'A', Money::fromMinor(100), 5),
            'p2' => Product::create('p2', 'B', Money::fromMinor(200), 3),
        ]);
        $transactionRunner = new InMemoryTransactionRunner();

        $validator = $this->createMock(CommandValidatorInterface::class);
        $handler = new ReserveStockCommandHandler($repo, $validator, $transactionRunner);

        ($handler)(new ReserveStockCommand([
            ['product_id' => 'p1', 'quantity' => 2],
            ['product_id' => 'p2', 'quantity' => 1],
        ]));

        self::assertSame(2, $repo->get('p1')->getOnHold());
        self::assertSame(1, $repo->get('p2')->getOnHold());
    }

    public function test_throws_on_unknown_product(): void
    {
        $repo = new InMemoryProductRepository([]);
        $tx = new InMemoryTransactionRunner();

        $validator = $this->createMock(CommandValidatorInterface::class);
        $handler = new ReserveStockCommandHandler($repo, $validator, $tx);

        $this->expectException(\DomainException::class);
        ($handler)(new ReserveStockCommand([
            ['product_id' => 'unknown', 'quantity' => 1],
        ]));
    }
}
