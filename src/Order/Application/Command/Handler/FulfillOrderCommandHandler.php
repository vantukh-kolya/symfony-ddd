<?php

namespace App\Order\Application\Command\Handler;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Order\Application\Port\ReservationCommitterPort;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            throw new NotFoundHttpException();
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
        $request = new CommitReservedStockForOrderRequest($order->getId(), $items);
        $stockFulfillmentResult = $this->stock->commitReservedItemsForOrder($request);
        $this->transactionRunner->run(function () use ($order, $stockFulfillmentResult) {
            if ($stockFulfillmentResult->success) {
                $order->fulfill();
            } else {
                $order->setFailed();
            }
        });
    }

}
