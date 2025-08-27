<?php

namespace App\Order\Application\Command\Handler;

use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\StockReservationPort;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\ValueObject\OrderLine;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;
use App\SharedKernel\Domain\ValueObject\Money;

class CreateOrderCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private TransactionRunnerInterface $transactionRunner,
        private StockReservationPort $reservation
    ) {
    }

    public function __invoke(CreateOrderCommand $command): Order
    {
        $order = $this->createOrder($command);
        $this->reserveProducts($order);

        return $order;
    }

    private function createOrder(CreateOrderCommand $command): Order
    {
        return $this->transactionRunner->run(function () use ($command) {
            $lines = array_map(
                fn(array $p) => new OrderLine((string)$p['product_id'], (int)$p['quantity'], Money::fromInt($p['price'])),
                $command->getProducts()
            );
            $order = Order::create(Money::fromInt($command->getAmountToPay()), ...$lines);

            $this->orderRepository->add($order);

            return $order;
        });
    }

    private function reserveProducts(Order $order): void
    {
        $items = [];
        foreach ($order->getItems() as $i) {
            $items[] = ['product_id' => (string)$i->getProductId(), 'quantity' => $i->getQuantity()];
        }
        $reservationResult = $this->reservation->reserve(new ReservationRequest($order->getId(), $items));
        $this->transactionRunner->run(function () use ($order, $reservationResult) {
            if ($reservationResult->success) {
                $order->setReserved();
            } else {
                $order->setFailed();
            }
        });
    }
}
