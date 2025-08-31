<?php

namespace App\Catalogue\Infrastructure\Ohs;

use App\Catalogue\Application\Command\FulfillStockReservationCommand;
use App\Catalogue\Application\Command\Handler\FulfillStockReservationCommandHandler;
use App\Catalogue\Contracts\Reservation\CatalogueFulfillReservationRequest;
use App\Catalogue\Contracts\Reservation\FulfillReservationResult;
use App\Catalogue\Contracts\Reservation\ReservationFulfillmentPort;

class StockReservationFulfillmentPort implements ReservationFulfillmentPort
{
    public function __construct(private FulfillStockReservationCommandHandler $handler)
    {
    }

    public function fulfill(CatalogueFulfillReservationRequest $request): FulfillReservationResult
    {
        try {
            ($this->handler)(new FulfillStockReservationCommand($request->items));
            return FulfillReservationResult::ok();
        } catch (\Throwable $e) {
            return FulfillReservationResult::fail($e->getMessage());
        }
    }

}
