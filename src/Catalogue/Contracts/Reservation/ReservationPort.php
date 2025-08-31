<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationPort
{
    public function reserve(CatalogueReserveStockRequest $request): CatalogueReservationResult;
}
