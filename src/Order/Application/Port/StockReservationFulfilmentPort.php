<?php

namespace App\Order\Application\Port;

use App\Order\Application\Port\Dto\FulfillReservationRequest;
use App\Order\Application\Port\Dto\ReservationFulfilmentResult;

interface StockReservationFulfilmentPort
{
    public function fulfill(FulfillReservationRequest $request): ReservationFulfilmentResult;
}
