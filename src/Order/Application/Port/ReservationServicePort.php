<?php

namespace App\Order\Application\Port;

use App\Catalogue\Contracts\Reservation\ReservationResult;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;

interface ReservationServicePort
{
    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
