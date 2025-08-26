<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Application\Port\CatalogueReservationDriver;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;
use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\Dto\ReservationResult;
use App\Order\Application\Port\StockReservationPort;

readonly class CatalogueStockReservationAdapter implements StockReservationPort
{
    public function __construct(private CatalogueReservationDriver $driver)
    {
    }

    public function reserve(ReservationRequest $request): ReservationResult
    {
        $catalogueRequest = new ReserveStockForOrderRequest(
            $request->orderId,
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items)
        );
        $catRes = $this->driver->reserveByOrder($catalogueRequest);
        return $catRes->success ? ReservationResult::ok() : ReservationResult::fail($catRes->reason);
    }
}
