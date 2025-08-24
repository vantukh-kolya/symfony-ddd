<?php

namespace App\Catalogue\Infrastructure\Api;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\CommitReservedStockForOrderRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitterInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitResult;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class ReservationCommitterDoctrine implements ReservationCommitterInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function commitReservedItemsForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult
    {
        return $this->transactionRunner->run(function () use ($request) {
            foreach ($request->items as $item) {
                $product = $this->productRepository->get($item['product_id']);
                if ($product === null) {
                    return ReservationCommitResult::fail('Product not found');
                }
                if ($item['quantity'] <= 0) {
                    return ReservationCommitResult::fail('Invalid quantity');
                }

                $product->commitReservation((int)$item['quantity']);
            }
            return ReservationCommitResult::ok();
        });
    }

}
