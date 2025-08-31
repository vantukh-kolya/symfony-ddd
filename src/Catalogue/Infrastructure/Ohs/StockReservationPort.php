<?php

namespace App\Catalogue\Infrastructure\Ohs;

use App\Catalogue\Application\Command\Handler\ReserveStockCommandHandler;
use App\Catalogue\Application\Command\ReserveStockCommand;
use App\Catalogue\Contracts\Reservation\CatalogueReservationResult;
use App\Catalogue\Contracts\Reservation\ReservationPort;
use App\Catalogue\Contracts\Reservation\CatalogueReserveStockRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StockReservationPort implements ReservationPort
{
    public function __construct(private ReserveStockCommandHandler $handler, private ValidatorInterface $validator)
    {
    }

    public function reserve(CatalogueReserveStockRequest $request): CatalogueReservationResult
    {
        try {
            $command = new ReserveStockCommand($request->items);
            $errors = $this->validator->validate($command);
            if ($errors->count() === 0) {
                ($this->handler)($command);
                return CatalogueReservationResult::ok();
            } else {
                return CatalogueReservationResult::fail("Invalid request");
            }
        } catch (\Throwable $e) {
            return CatalogueReservationResult::fail($e->getMessage());
        }
    }
}
