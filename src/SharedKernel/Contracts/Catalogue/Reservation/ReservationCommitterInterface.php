<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

interface ReservationCommitterInterface
{
    public function commitReservedItemsForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
