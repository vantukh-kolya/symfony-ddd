<?php

namespace App\Order\Infrastructure\Doctrine\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: OrderRepositoryInterface::class)]
class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function get(string $orderId): ?Order
    {
        return $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $orderId]);
    }

    public function add(Order $order): void
    {
        $this->entityManager->persist($order);
    }

}
