<?php

namespace App\Order\Integration\Catalogue;

use App\Catalogue\Contracts\Reservation\ReservationPort;
use App\Catalogue\Contracts\Reservation\CatalogueReserveStockRequest;
use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\Dto\ReservationResult;
use App\Order\Application\Port\StockReservationPort;

readonly class StockReservationAdapter implements StockReservationPort
{
    public function __construct(private ReservationPort $reservation)
    {
    }

    public function reserve(ReservationRequest $request): ReservationResult
    {
        $catalogueRequest = new CatalogueReserveStockRequest(
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items),
            ['order_id' => $request->orderId]
        );
        $reservationResult = $this->reservation->reserve($catalogueRequest);
        return $reservationResult->success ? ReservationResult::ok() : ReservationResult::fail($reservationResult->reason);
    }
}
