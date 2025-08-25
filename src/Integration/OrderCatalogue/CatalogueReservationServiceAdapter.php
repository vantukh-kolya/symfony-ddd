<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\ReservationResult;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;
use App\Order\Application\Port\ReservationServicePort;

class CatalogueReservationServiceAdapter implements ReservationServicePort
{
    public function __construct(private CatalogueReservationDriver $reservationDriver)
    {
    }

    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult
    {
        return $this->reservationDriver->reserveByOrder($request);
    }

}
