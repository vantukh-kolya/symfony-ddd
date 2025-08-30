<?php

namespace App\Order\Application\Query\Handler;

use App\Order\Application\Query\GetOrdersQuery;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Enum\OrderStatus;
use App\Order\Domain\Repository\OrderRepositoryInterface;

class GetOrdersQueryHandler
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function __invoke(GetOrdersQuery $query): array
    {
        $result = [];
        $status = null;
        if ($query->status) {
            $status = OrderStatus::tryFrom($query->status);
        }
        $collection = $this->orderRepository->getCollection($status);
        if (!empty($collection)) {
            foreach ($collection as $order) {
                $result[] = [
                    'id' => $order->getId(),
                    'status' => $order->getStatus(),
                    'items' => $this->getItems($order)
                ];
            }
        }
        return $result;
    }

    private function getItems(Order $order): array
    {
        $items = [];
        foreach ($order->getItems() as $item) {
            $items[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice()->toFloat()
            ];
        }

        return $items;
    }
}
