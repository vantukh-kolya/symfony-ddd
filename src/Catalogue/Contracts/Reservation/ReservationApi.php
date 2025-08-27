<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationApi
{
    public function reserveStockForOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
