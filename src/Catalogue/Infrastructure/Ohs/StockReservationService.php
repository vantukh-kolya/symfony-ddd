<?php

namespace App\Catalogue\Infrastructure\Ohs;

use App\Catalogue\Application\Command\Handler\ReserveStockForOrderCommandHandler;
use App\Catalogue\Application\Command\ReserveStockForOrderCommand;
use App\Catalogue\Contracts\Reservation\ReservationResult;
use App\Catalogue\Contracts\Reservation\ReservationService;
use App\Catalogue\Contracts\Reservation\ReserveStockForOrderRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StockReservationService implements ReservationService
{
    public function __construct(private ReserveStockForOrderCommandHandler $handler, private ValidatorInterface $validator)
    {
    }

    public function reserveStockForOrder(ReserveStockForOrderRequest $request): ReservationResult
    {
        try {
            $command = new ReserveStockForOrderCommand($request->orderId, $request->items);
            $errors = $this->validator->validate($command);
            if ($errors->count() === 0) {
                ($this->handler)($command);
                return ReservationResult::ok();
            } else {
                return ReservationResult::fail("Invalid request");
            }
        } catch (\Throwable $e) {
            return ReservationResult::fail($e->getMessage());
        }
    }
}
