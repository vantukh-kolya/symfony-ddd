<?php

namespace App\Catalogue\Application\Command\Handler;

use App\Catalogue\Application\Command\CommandValidatorInterface;
use App\Catalogue\Application\Command\CreateProductCommand;
use App\Catalogue\Domain\Entity\Product;
use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use App\SharedKernel\Domain\ValueObject\Money;

class CreateProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CommandValidatorInterface $commandValidator,
        private TransactionRunnerInterface $transactionRunner
    ) {
    }

    public function __invoke(CreateProductCommand $command): Product
    {
        $this->commandValidator->assert($command);
        return $this->transactionRunner->run(function () use ($command) {
            $product = Product::create($command->id, $command->name, Money::fromMinor($command->price), $command->onHand);
            $this->productRepository->add($product);

            return $product;
        });
    }
}
