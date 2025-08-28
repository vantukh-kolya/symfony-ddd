<?php

namespace App\Catalogue\Application\Command\Handler;

use App\Catalogue\Application\Command\ReserveStockForOrderCommand;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class ReserveStockForOrderCommandHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function __invoke(ReserveStockForOrderCommand $command): void
    {
        $this->transactionRunner->run(function () use ($command) {
            foreach ($command->items as $item) {
                if (!isset($item['quantity']) || !isset($item['product_id'])) {
                    throw new \DomainException('Invalid order items');
                }

                $product = $this->productRepository->get($item['product_id']);
                if (!$product) {
                    throw new \DomainException('Unknown products');
                }
                $product->hold($item['quantity']);
            }
        });
    }
}
