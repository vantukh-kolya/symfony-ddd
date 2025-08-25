<?php

namespace App\Catalogue\Infrastructure\Persistence\Doctrine\Repository;

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
        return $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
    }

    public function getCollection(bool $onlyAvailable, ?int $maxPrice): array
    {
        $qb = $this->entityManager->createQueryBuilder()->select("p")->from(Product::class, "p");
        if ($onlyAvailable) {
            $qb->andWhere("p.on_hand - p.on_hold > 0");
        }
        if ($maxPrice) {
            $qb->andWhere("p.price <= :maxPrice")->setParameter("maxPrice", $maxPrice);
        }

        return $qb->getQuery()->getResult();
    }

    public function add(Product $product): void
    {
        $this->entityManager->persist($product);
    }

}
