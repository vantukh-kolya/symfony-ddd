<?php

namespace App\Catalogue\Application\Command;

interface CommandValidatorInterface
{
    public function assert(object $command, array $groups = []): void;
}
