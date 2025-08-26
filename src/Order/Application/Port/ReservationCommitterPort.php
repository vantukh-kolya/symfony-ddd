<?php

namespace App\Order\Application\Port;

use App\Order\Application\Port\Dto\ReservationCommitRequest;
use App\Order\Application\Port\Dto\ReservationCommitResult;

interface ReservationCommitterPort
{
    public function commitReservation(ReservationCommitRequest $request): ReservationCommitResult;
}
