<?php

namespace App\SharedKernel\Http;

readonly class ResponseEnvelope
{
    private function __construct(public int $status, public array $body)
    {
    }

    public static function success(array $data = [], int $status = 200, array $meta = []): self
    {
        $b = ['data' => $data];
        if ($meta !== []) {
            $b['meta'] = $meta;
        }
        return new self($status, $b);
    }

    public static function error(int $status, string $message, string $type = 'error', ?string $traceId = null, array $details = []): self
    {
        $err = ['code' => $status, 'message' => $message, 'type' => $type];
        if ($traceId) {
            $err['trace_id'] = $traceId;
        }
        if ($details !== []) {
            $err['details'] = $details;
        }
        return new self($status, ['error' => $err]);
    }
}
