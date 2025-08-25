<?php

namespace App\Order\Application\Port;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitResult;

interface ReservationCommitterPort
{
    public function commitReservedItemsForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
