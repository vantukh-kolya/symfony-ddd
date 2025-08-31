<?php

namespace App\Catalogue\Application\Command\Handler;

use App\Catalogue\Application\Command\ReserveStockCommand;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class ReserveStockCommandHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository, private TransactionRunnerInterface $transactionRunner)
    {
    }

    public function __invoke(ReserveStockCommand $command): void
    {
        $this->transactionRunner->run(function () use ($command) {
            foreach ($command->items as $item) {
                $product = $this->productRepository->get($item['product_id']);
                if (!$product) {
                    throw new \DomainException('Unknown products');
                }
                $product->hold($item['quantity']);
            }
        });
    }
}
