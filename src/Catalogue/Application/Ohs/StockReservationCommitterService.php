<?php

namespace App\Catalogue\Application\Ohs;

use App\Catalogue\Application\Command\CommitReservedStockForOrderCommand;
use App\Catalogue\Application\Command\Handler\CommitReservedStockForOrderCommandHandler;
use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitResult;
use App\Catalogue\Contracts\Reservation\ReservationCommitterService;

class StockReservationCommitterService implements ReservationCommitterService
{
    public function __construct(private CommitReservedStockForOrderCommandHandler $handler)
    {
    }

    public function commitReservedStockForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult
    {
        try {
            ($this->handler)(new CommitReservedStockForOrderCommand($request->orderId, $request->items));
            return ReservationCommitResult::ok();
        } catch (\Throwable $e) {
            return ReservationCommitResult::fail($e->getMessage());
        }
    }

}
