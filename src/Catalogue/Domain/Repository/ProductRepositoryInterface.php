<?php

namespace App\Catalogue\Domain\Repository;

use App\Catalogue\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function get(string $productId): ?Product;

    public function getCollection(bool $onlyAvailable, ?int $maxPrice): array;

    public function add(Product $product): void;
}
