<?php

namespace App\Catalogue\Application\Query\Handler;

use App\Catalogue\Application\Query\GetProductsQuery;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;

class GetProductsQueryHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function __invoke(GetProductsQuery $query): array
    {
        $result = [];
        $collection = $this->productRepository->getCollection($query->onlyAvailable, $query->maxPrice);
        if (!empty($collection)) {
            foreach ($collection as $product) {
                $result[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice()->toMajorString(),
                    'quantity' => $product->getAvailable()
                ];
            }
        }

        return $result;
    }
}
