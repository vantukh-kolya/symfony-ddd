<?php

namespace App\Catalogue\Infrastructure\Adapters;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitResult;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\Integration\OrderCatalogue\CatalogueReservationCommitterDriver as ReservationCommitterDriverInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class DoctrineReservationCommitterDriver implements ReservationCommitterDriverInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function reserveByOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult
    {
        try {
            $this->transactionRunner->run(function () use ($request) {
                foreach ($request->items as $item) {
                    $product = $this->productRepository->get($item['product_id']);
                    if (!$product) {
                        throw new \DomainException('Product not found:' . $item['product_id']);
                    }
                    $product->commitReservation($item['quantity']);
                }
            });
            return ReservationCommitResult::ok();
        } catch (\DomainException $e) {
            return ReservationCommitResult::fail($e->getMessage());
        }
    }

}
