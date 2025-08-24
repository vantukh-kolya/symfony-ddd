<?php

namespace App\Catalogue\Infrastructure\Api;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Contracts\Catalogue\Stock\OrderReservationCommitRequest;
use App\SharedKernel\Contracts\Catalogue\Stock\ReservedStockCommitterInterface;
use App\SharedKernel\Contracts\Catalogue\Stock\StockCommitResult;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: ReservedStockCommitterInterface::class)]
class ReservedStockCommitterInProcess implements ReservedStockCommitterInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function commitReservedItemsForOrder(OrderReservationCommitRequest $request): StockCommitResult
    {
        return $this->transactionRunner->run(function () use ($request) {
            foreach ($request->items as $item) {
                $product = $this->productRepository->get($item['product_id']);
                if ($product === null) {
                    return StockCommitResult::fail('Product not found');
                }
                if ($item['quantity'] <= 0) {
                    return StockCommitResult::fail('Invalid quantity');
                }

                $product->commitReservation((int)$item['quantity']);
            }
            return StockCommitResult::ok();
        });
    }

}
