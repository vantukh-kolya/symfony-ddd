<?php

namespace App\SharedKernel\Infrastructure\Persistence\Doctrine;

use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: TransactionRunnerInterface::class)]
class DoctrineTransactionRunner implements TransactionRunnerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function run(callable $callback)
    {
        return $this->entityManager->wrapInTransaction(function () use ($callback) {
            $result = $callback();
            $this->entityManager->flush();
            return $result;
        });
    }
}
