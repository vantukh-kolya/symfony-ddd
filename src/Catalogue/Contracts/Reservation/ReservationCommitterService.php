<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationCommitterService
{
    public function commitReservedStockForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
