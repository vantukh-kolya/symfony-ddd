<?php

namespace App\Order\Infrastructure\Validation;

use App\Order\Application\Command\CommandValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyCommandValidator implements CommandValidatorInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function assert(object $command, array $groups = []): void
    {
        $viol = $this->validator->validate($command, null, $groups);
        if (count($viol) > 0) {
            throw new ValidationFailedException($command, $viol);
        }
    }

}
