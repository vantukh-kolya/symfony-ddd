<?php

namespace App\Tests\Application\Query;

use App\Catalogue\Application\Query\GetProductsQuery;
use App\Catalogue\Application\Query\Handler\GetProductsQueryHandler;
use App\Catalogue\Domain\Entity\Product;
use App\SharedKernel\Domain\ValueObject\Money;
use App\Tests\Support\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

final class GetProductsQueryHandlerTest extends TestCase
{
    public function test_maps_products_from_repository(): void
    {
        $products = [
            Product::create('p1', 'A', Money::fromInt(1999), 5),
            Product::create('p2', 'B', Money::fromInt(2500), 0),
        ];

        $repo = new InMemoryProductRepository($products);
        $handler = new GetProductsQueryHandler($repo);

        $result = ($handler)(new GetProductsQuery());

        self::assertCount(count($products), $result);

        foreach ($products as $i => $product) {
            self::assertSame($product->getId(), $result[$i]['id']);
            self::assertSame($product->getName(), $result[$i]['name']);
            self::assertSame($product->getPrice()->toFloat(), $result[$i]['price']);
            self::assertSame($product->getAvailable(), $result[$i]['quantity']);
        }
    }

    public function test_returns_empty_array_when_no_products(): void
    {
        $repo = new InMemoryProductRepository([]);
        $handler = new GetProductsQueryHandler($repo);

        $result = ($handler)(new GetProductsQuery(false, null));

        self::assertSame([], $result);
    }
}
