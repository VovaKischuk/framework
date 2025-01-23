<?php

declare(strict_types=1);

namespace Framework\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    private function __construct($data = null, int $status = self::HTTP_OK, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }

    public static function fromPayload(array $payload, int $status = self::HTTP_OK): self
    {
        return new self($payload, $status);
    }

    public static function empty(int $status): self
    {
        return new self(null, $status);
    }
}
