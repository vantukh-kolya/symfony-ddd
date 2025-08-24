<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

interface ReservationServiceInterface
{
    public function reserveByOrder(ReserveStockForOrderRequest $request): ReservationResult;
}
