<?php

namespace App\Catalogue\Infrastructure\Reservation;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\Integration\OrderCatalogue\CatalogueReservationDriver as CatalogueReservationDriverInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReserveStockForOrderRequest;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class DoctrineReservationDriver implements CatalogueReservationDriverInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult
    {
        try {
            $this->transactionRunner->run(function () use ($request) {
                foreach ($request->items as $item) {
                    $product = $this->productRepository->get($item['product_id']);
                    if (!$product) {
                        throw new \DomainException('Product not found:' . $item['product_id']);
                    }
                    $product->hold($item['quantity']);
                }
            });
            return ReservationResult::ok();
        } catch (\DomainException $e) {
            return ReservationResult::fail($e->getMessage());
        }
    }
}
