<?php

namespace App\Tests\Support;

use App\SharedKernel\Domain\Persistence\TransactionRunnerInterface;

class InMemoryTransactionRunner implements TransactionRunnerInterface
{
    public bool $committed = false;

    public function run(callable $callback)
    {
        $res = $callback();
        $this->committed = true;
        return $res;
    }
}
