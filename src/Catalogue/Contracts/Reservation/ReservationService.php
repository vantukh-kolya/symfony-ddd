<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationService
{
    public function reserveStockForOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
