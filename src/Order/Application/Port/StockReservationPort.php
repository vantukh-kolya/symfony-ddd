<?php

namespace App\Order\Application\Port;

use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\Dto\ReservationResult;

interface StockReservationPort
{
    public function reserve(ReservationRequest $request): ReservationResult;
}
