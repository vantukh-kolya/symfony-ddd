<?php

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Port\Dto\ReservationCommitRequest;
use App\Order\Application\Port\ReservationCommitterPort;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class FulfillOrderCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private TransactionRunnerInterface $transactionRunner,
        private ReservationCommitterPort $stock
    ) {
    }

    public function __invoke(string $orderId): void
    {
        $order = $this->orderRepository->get($orderId);
        if (!$order) {
            throw new \DomainException("Order not found");
        }
        if ($order->isFulfilled()) {
            throw new \DomainException("Order already fulfilled");
        }
        if (!$order->isReserved()) {
            throw new \DomainException("Order not reserved");
        }
        $items = [];
        foreach ($order->getItems() as $i) {
            $items[] = ['product_id' => (string)$i->getProductId(), 'quantity' => $i->getQuantity()];
        }
        $stockFulfillmentResult = $this->stock->commitReservation(new ReservationCommitRequest($order->getId(), $items));
        $this->transactionRunner->run(function () use ($order, $stockFulfillmentResult) {
            if ($stockFulfillmentResult->success) {
                $order->fulfill();
            } else {
                $order->setFailed();
            }
        });
    }

}
