<?php

namespace App\Catalogue\Contracts\Reservation;

interface CatalogueStockReservationPort
{
    public function reserve(CatalogueReserveStockRequest $request): CatalogueReservationResult;
}
