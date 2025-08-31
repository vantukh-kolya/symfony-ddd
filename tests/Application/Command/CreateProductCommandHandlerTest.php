<?php

namespace App\Tests\Application\Command;

use App\Catalogue\Application\Command\CreateProductCommand;
use App\Catalogue\Application\Command\Handler\CreateProductCommandHandler;
use App\Catalogue\Domain\Entity\Product;
use App\Catalogue\Domain\Exception\EmptyProductNameException;
use App\Tests\Support\InMemoryProductRepository;
use App\Tests\Support\InMemoryTransactionRunner;
use PHPUnit\Framework\TestCase;

final class CreateProductCommandHandlerTest extends TestCase
{
    public function test_creates_product(): void
    {
        $repo = new InMemoryProductRepository();
        $transactionRunner = new InMemoryTransactionRunner();

        $handler = new CreateProductCommandHandler($repo, $transactionRunner);

        $cmd = new CreateProductCommand('p1', 'Apple', 199, 10);
        $product = $handler($cmd);

        self::assertInstanceOf(Product::class, $product);
        self::assertSame($cmd->id, $product->getId());
        self::assertSame($cmd->name, $product->getName());
        self::assertSame($cmd->onHand, $product->getOnHand());
        self::assertSame($cmd->price, $product->getPrice()->toMinor());
        self::assertTrue($transactionRunner->committed);
    }

    public function test_throws_domain_exception_on_invalid_name(): void
    {
        $repo = new InMemoryProductRepository();
        $tx = new InMemoryTransactionRunner();

        $handler = new CreateProductCommandHandler($repo, $tx);

        $this->expectException(EmptyProductNameException::class);
        $handler(new CreateProductCommand('p2', '', 100, 5));
    }
}
