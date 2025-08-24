<?php

namespace App\Integration\OrderCatalogue;

use App\Order\Application\Port\ReservationServicePort;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReserveStockForOrderRequest;

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
