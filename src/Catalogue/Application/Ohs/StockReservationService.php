<?php

namespace App\Catalogue\Application\Ohs;

use App\Catalogue\Application\Command\Handler\ReserveStockForOrderCommandHandler;
use App\Catalogue\Application\Command\ReserveStockForOrderCommand;
use App\Catalogue\Contracts\Reservation\ReservationService;
use App\Catalogue\Contracts\Reservation\ReservationResult;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;

class StockReservationService implements ReservationService
{
    public function __construct(private ReserveStockForOrderCommandHandler $handler)
    {
    }

    public function reserveStockForOrder(ReserveStockForOrderRequest $request): ReservationResult
    {
        try {
            ($this->handler)(new ReserveStockForOrderCommand($request->orderId, $request->items));
            return ReservationResult::ok();
        } catch (\Throwable $e) {
            return ReservationResult::fail($e->getMessage());
        }
    }
}
