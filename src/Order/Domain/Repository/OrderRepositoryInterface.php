<?php

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function add(Order $order): void;
}
