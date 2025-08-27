<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\ReservationApi;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;
use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\Dto\ReservationResult;
use App\Order\Application\Port\StockReservationPort;

readonly class CatalogueStockReservationAdapter implements StockReservationPort
{
    public function __construct(private ReservationApi $reservationApi)
    {
    }

    public function reserve(ReservationRequest $request): ReservationResult
    {
        $catalogueRequest = new ReserveStockForOrderRequest(
            $request->orderId,
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items)
        );
        $catRes = $this->reservationApi->reserveStockForOrder($catalogueRequest);
        return $catRes->success ? ReservationResult::ok() : ReservationResult::fail($catRes->reason);
    }
}
