<?php

namespace App\Order\Integration\Catalogue;

use App\Catalogue\Contracts\Reservation\ReservationService;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;
use App\Order\Application\Port\Dto\ReservationRequest;
use App\Order\Application\Port\Dto\ReservationResult;
use App\Order\Application\Port\StockReservationPort;

readonly class StockReservationAdapter implements StockReservationPort
{
    public function __construct(private ReservationService $reservationService)
    {
    }

    public function reserve(ReservationRequest $request): ReservationResult
    {
        $catalogueRequest = new ReserveStockForOrderRequest(
            $request->orderId,
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items)
        );
        $catRes = $this->reservationService->reserveStockForOrder($catalogueRequest);
        return $catRes->success ? ReservationResult::ok() : ReservationResult::fail($catRes->reason);
    }
}
