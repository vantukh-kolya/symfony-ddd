<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitResult;

interface CatalogueReservationCommitterDriver
{
    public function reserveByOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
