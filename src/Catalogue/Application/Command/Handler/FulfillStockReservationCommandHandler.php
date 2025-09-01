<?php

namespace App\Catalogue\Application\Command\Handler;

use App\Catalogue\Application\Command\CommandValidatorInterface;
use App\Catalogue\Application\Command\FulfillStockReservationCommand;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class FulfillStockReservationCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CommandValidatorInterface $commandValidator,
        private TransactionRunnerInterface $transactionRunner
    ) {
    }

    public function __invoke(FulfillStockReservationCommand $command): void
    {
        $this->commandValidator->assert($command);
        $this->transactionRunner->run(function () use ($command) {
            foreach ($command->items as $item) {
                $product = $this->productRepository->get($item['product_id']);
                if (!$product) {
                    throw new \DomainException('Product not found:' . $item['product_id']);
                }
                $product->commitReservation($item['quantity']);
            }
        });
    }
}
