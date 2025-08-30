<?php

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Enum\OrderStatus;

interface OrderRepositoryInterface
{
    public function get(string $orderId): ?Order;

    public function getCollection(?OrderStatus $status): array;

    public function add(Order $order): void;
}
