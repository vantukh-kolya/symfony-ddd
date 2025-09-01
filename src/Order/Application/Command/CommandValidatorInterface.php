<?php

namespace App\Order\Application\Command;

interface CommandValidatorInterface
{
    public function assert(object $command, array $groups = []): void;
}
