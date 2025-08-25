<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\ReservationResult;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;

interface CatalogueReservationDriver
{
    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
