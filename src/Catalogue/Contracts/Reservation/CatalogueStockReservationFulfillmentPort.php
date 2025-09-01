<?php

namespace App\Catalogue\Contracts\Reservation;

interface CatalogueStockReservationFulfillmentPort
{
    public function fulfill(CatalogueFulfillReservationRequest $request): FulfillReservationResult;
}
