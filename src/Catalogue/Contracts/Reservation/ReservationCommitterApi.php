<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationCommitterApi
{
    public function commitReservedStockForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
