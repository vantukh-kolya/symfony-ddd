<?php

namespace App\Catalogue\Infrastructure\Api;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationServiceInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReserveStockForOrderRequest;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class ReservationServiceDoctrine implements ReservationServiceInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult
    {
        return $this->transactionRunner->run(function () use ($request) {
            foreach ($request->items as $item) {
                $product = $this->productRepository->get($item['product_id']);
                if ($product === null) {
                    return ReservationResult::fail('Product not found');
                }
                if ($item['quantity'] <= 0) {
                    return ReservationResult::fail('Invalid quantity');
                }

                $product->hold((int)$item['quantity']);
            }
            return ReservationResult::ok();
        });
    }

}
