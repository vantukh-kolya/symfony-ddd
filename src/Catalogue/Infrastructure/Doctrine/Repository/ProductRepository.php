<?php

namespace App\Catalogue\Infrastructure\Doctrine\Repository;

use App\Catalogue\Domain\Entity\Product;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: ProductRepositoryInterface::class)]
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function get(string $productId): ?Product
    {
        return $this->entityManager->find(Product::class, $productId);
    }

    public function add(Product $product): void
    {
        $this->entityManager->persist($product);
    }

}
