<?php

namespace App\SharedKernel\Contracts\Catalogue\Reservation;

interface ProductReservationInterface
{
    public function reserveByOrder(OrderReserveRequest $request): ReservationResult;
}
