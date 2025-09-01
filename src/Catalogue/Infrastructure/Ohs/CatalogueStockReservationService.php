<?php

namespace App\Catalogue\Infrastructure\Ohs;

use App\Catalogue\Application\Command\Handler\ReserveStockCommandHandler;
use App\Catalogue\Application\Command\ReserveStockCommand;
use App\Catalogue\Contracts\Reservation\CatalogueReservationResult;
use App\Catalogue\Contracts\Reservation\CatalogueStockReservationPort;
use App\Catalogue\Contracts\Reservation\CatalogueReserveStockRequest;

class CatalogueStockReservationService implements CatalogueStockReservationPort
{
    public function __construct(private ReserveStockCommandHandler $handler)
    {
    }

    public function reserve(CatalogueReserveStockRequest $request): CatalogueReservationResult
    {
        try {
            $command = new ReserveStockCommand($request->items);
            ($this->handler)($command);
            return CatalogueReservationResult::ok();
        } catch (\Throwable $e) {
            return CatalogueReservationResult::fail($e->getMessage());
        }
    }
}
