<?php

namespace App\SharedKernel\Infrastructure\Http\Exception;

use App\SharedKernel\Infrastructure\Http\SymfonyErrorResponder;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function __construct(private SymfonyErrorResponder $errorResponseFactory)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        if ($e instanceof ValidationFailedException) {
            $event->setResponse($this->errorResponseFactory->validationError($e->getViolations()));
        }
    }
}
