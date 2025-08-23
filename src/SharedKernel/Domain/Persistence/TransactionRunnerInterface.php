<?php

namespace App\SharedKernel\Domain\Persistence;

interface TransactionRunnerInterface
{
    public function run(callable $callback);
}
