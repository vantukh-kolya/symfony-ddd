<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Domain\Repository\ProductRepositoryInterface;
use App\Order\Application\Port\ReservationCommitterPort;
use App\SharedKernel\Contracts\Catalogue\Reservation\CommitReservedStockForOrderRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitResult;
use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

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
