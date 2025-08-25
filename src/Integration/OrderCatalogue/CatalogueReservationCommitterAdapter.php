<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitResult;
use App\Order\Application\Port\ReservationCommitterPort;

class CatalogueReservationCommitterAdapter implements ReservationCommitterPort
{
    public function __construct(private CatalogueReservationCommitterDriver $reservationCommitterDriver)
    {
    }

    public function commitReservedItemsForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult
    {
        return $this->reservationCommitterDriver->reserveByOrder($request);
    }

}
