<?php

namespace App\Integration\OrderCatalogue;

use App\Order\Application\Port\ReservationCommitterPort;
use App\SharedKernel\Contracts\Catalogue\Reservation\CommitReservedStockForOrderRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitResult;

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
