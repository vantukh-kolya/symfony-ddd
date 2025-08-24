<?php

namespace App\Order\Application\Port;

use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationResult;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReserveStockForOrderRequest;

interface ReservationServicePort
{
    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
