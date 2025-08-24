<?php

namespace App\Integration\OrderCatalogue;

use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReserveStockForOrderRequest;

interface CatalogueReservationDriver
{
    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
