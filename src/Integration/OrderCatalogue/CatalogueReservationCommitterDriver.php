<?php

namespace App\Integration\OrderCatalogue;

use App\SharedKernel\Contracts\Catalogue\Reservation\CommitReservedStockForOrderRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitResult;

interface CatalogueReservationCommitterDriver
{
    public function reserveByOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
