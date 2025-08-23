<?php

namespace App\Catalogue\Presentation\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class HttpResponseFactory
{
    public function success(array $data = [], int $status = JsonResponse::HTTP_OK, array $meta = []): JsonResponse
    {
        $response = ['data' => $data];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return new JsonResponse($response, $status);
    }

    public function error(
        int $code,
        string $message,
        string $type = 'error',
        ?string $traceId = null,
        array $details = []
    ): JsonResponse {
        $payload = [
            'error' => [
                'code' => $code,
                'message' => $message,
                'type' => $type,
            ]
        ];

        if ($traceId) {
            $payload['error']['trace_id'] = $traceId;
        }

        if (!empty($details)) {
            $payload['error']['details'] = $details;
        }

        return new JsonResponse($payload, $code);
    }

    public function validationError(
        ConstraintViolationListInterface $violations,
        ?string $traceId = null
    ): JsonResponse {
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
