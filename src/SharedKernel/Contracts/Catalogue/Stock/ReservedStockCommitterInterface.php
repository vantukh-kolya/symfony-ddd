<?php

namespace App\SharedKernel\Contracts\Catalogue\Stock;

interface ReservedStockCommitterInterface
{
    public function commitReservedItemsForOrder(OrderReservationCommitRequest $request): StockCommitResult;
}

