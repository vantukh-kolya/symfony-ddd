<?php

namespace App\Tests\Support;

use App\Catalogue\Domain\Entity\Product;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    public array $products = [];

    public function __construct(array $products = [])
    {
        $this->products = $products;
    }

    public function add(Product $product): void
    {
        $this->products[] = ['add', $product->getId()];
    }

    public function getCollection(bool $onlyAvailable, ?int $maxPrice): array
    {
        return $this->products;
    }

    public function get(string $productId): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $productId) {
                return $product;
            }
        }

        return null;
    }
}
