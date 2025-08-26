<?php

namespace App\Integration\OrderCatalogue;

use App\Catalogue\Application\Port\CatalogueReservationCommitterDriver;
use App\Catalogue\Contracts\Reservation\CommitReservedStockForOrderRequest;
use App\Order\Application\Port\Dto\ReservationCommitRequest;
use App\Order\Application\Port\Dto\ReservationCommitResult;
use App\Order\Application\Port\ReservationCommitterPort;

class CatalogueReservationCommitterAdapter implements ReservationCommitterPort
{
    public function __construct(private CatalogueReservationCommitterDriver $committerDriver)
    {
    }

    public function commitReservation(ReservationCommitRequest $request): ReservationCommitResult
    {
        $catalogueRequest = new CommitReservedStockForOrderRequest(
            $request->orderId,
            array_map(fn($i) => ['product_id' => $i['product_id'], 'quantity' => $i['quantity']], $request->items)
        );
        $catRes = $this->committerDriver->reserveByOrder($catalogueRequest);
        return $catRes->success ? ReservationCommitResult::ok() : ReservationCommitResult::fail($catRes->reason);
    }

}
