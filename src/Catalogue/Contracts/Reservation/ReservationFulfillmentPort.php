<?php

namespace App\Catalogue\Contracts\Reservation;

interface ReservationFulfillmentPort
{
    public function fulfill(CatalogueFulfillReservationRequest $request): FulfillReservationResult;
}
