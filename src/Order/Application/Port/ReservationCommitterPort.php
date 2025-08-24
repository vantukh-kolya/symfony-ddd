<?php

namespace App\Order\Application\Port;

use App\SharedKernel\Contracts\Catalogue\Reservation\CommitReservedStockForOrderRequest;
use App\SharedKernel\Contracts\Catalogue\Reservation\ReservationCommitResult;

interface ReservationCommitterPort
{
    public function commitReservedItemsForOrder(CommitReservedStockForOrderRequest $request): ReservationCommitResult;
}
