<?php

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Port\Dto\FulfillReservationRequest;
use App\Order\Application\Port\StockReservationFulfilmentPort;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class FulfillOrderCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private TransactionRunnerInterface $transactionRunner,
        private StockReservationFulfilmentPort $stock
    ) {
    }

    public function __invoke(string $orderId): void
    {
        $order = $this->orderRepository->get($orderId);
        if (!$order) {
            throw new \DomainException("Order not found");
        }
        $items = [];
        foreach ($order->getItems() as $i) {
            $items[] = ['product_id' => (string)$i->getProductId(), 'quantity' => $i->getQuantity()];
        }
        $stockFulfillmentResult = $this->stock->commitReservation(new FulfillReservationRequest($items));
        $this->transactionRunner->run(function () use ($order, $stockFulfillmentResult) {
            if ($stockFulfillmentResult->success) {
                $order->fulfill();
            } else {
                $order->setFailed();
            }
        });
    }

}
