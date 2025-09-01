<?php

namespace App\Order\Integration\Catalogue;

use App\Catalogue\Contracts\Reservation\ReservationFulfillmentPort;
use App\Catalogue\Contracts\Reservation\CatalogueFulfillReservationRequest;
use App\Order\Application\Port\Dto\FulfillReservationRequest;
use App\Order\Application\Port\Dto\ReservationFulfilmentResult;
use App\Order\Application\Port\StockReservationFulfilmentPort;

readonly class StockReservationFulfilmentAdapter implements StockReservationFulfilmentPort
{
    public function __construct(private ReservationFulfillmentPort $reservationFulfillment)
    {
    }

    public function fulfill(FulfillReservationRequest $request): ReservationFulfilmentResult
    {
        $catalogueRequest =
            new CatalogueFulfillReservationRequest(
                array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items),
                ['order_id' => $request->orderId]
            );
        $fulfilmentResult = $this->reservationFulfillment->fulfill($catalogueRequest);
        return $fulfilmentResult->success ? ReservationFulfilmentResult::ok() : ReservationFulfilmentResult::fail($fulfilmentResult->reason);
    }

}
