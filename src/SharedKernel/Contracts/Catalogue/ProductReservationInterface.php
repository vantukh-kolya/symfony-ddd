<?php

namespace App\SharedKernel\Contracts\Catalogue;

interface ProductReservationInterface
{
    public function reserveByOrder(OrderReserveRequest $request): ReservationResult;

    public function release(string $orderId): ReservationResult;
}
