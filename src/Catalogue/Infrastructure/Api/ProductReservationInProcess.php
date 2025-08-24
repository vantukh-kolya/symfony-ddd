<?php

namespace App\Catalogue\Infrastructure\Api;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\OrderReserveRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ProductReservationInterface;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: ProductReservationInterface::class)]
class ProductReservationInProcess implements ProductReservationInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function reserveByOrder(OrderReserveRequest $request): ReservationResult
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
