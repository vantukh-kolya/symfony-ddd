<?php

namespace App\SharedKernel\Infrastructure\Http;

use App\SharedKernel\Http\ResponseEnvelope;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SymfonyErrorResponder
{
    public function error(int $code, string $message, string $type = 'error', ?string $traceId = null, array $details = []): JsonResponse
    {
        $envelope = ResponseEnvelope::error($code, $message, $type, $traceId, $details);
        return new JsonResponse($envelope->body, $envelope->status);
    }

    public function validationError(ConstraintViolationListInterface $violations, ?string $traceId = null): JsonResponse
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $this->error(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation failed', 'validation_error', $traceId, ['errors' => $errors]);
    }

}
