<?php

namespace App\Order\Integration\OrderCatalogue;

use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Catalogue\Contracts\Reservation\ReservationCommitterApi;
use App\Order\Application\Port\Dto\ReservationCommitRequest;
use App\Order\Application\Port\Dto\ReservationCommitResult;
use App\Order\Application\Port\ReservationCommitterPort;

class CatalogueReservationCommitterAdapter implements ReservationCommitterPort
{
    public function __construct(private ReservationCommitterApi $reservationCommitterApi)
    {
    }

    public function commitReservation(ReservationCommitRequest $request): ReservationCommitResult
    {
        $catalogueRequest = new CommitReservedStockForOrderRequest(
            $request->orderId,
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items)
        );
        $catRes = $this->reservationCommitterApi->commitReservedStockForOrder($catalogueRequest);
        return $catRes->success ? ReservationCommitResult::ok() : ReservationCommitResult::fail($catRes->reason);
    }

}
