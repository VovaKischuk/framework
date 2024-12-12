<?php

namespace Framework\Http;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    protected int $statusCode = 200;

    private const array STANDARD_PHRASES = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    ];

    public function __construct(
        int            $statusCode = 200,
        private array  $headers = [],
        private mixed  $body = null,
        private string $reasonPhrase = '',
        private string $protocolVersion = '1.1',
    )
    {
        parent::__construct($body, $protocolVersion, $headers);
        $this->setStatusCode($statusCode, $reasonPhrase);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): self
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return array_key_exists(\strtolower($name), $this->headers);
    }

    public function getHeader($name): array
    {
        return $this->headers[\strtolower($name)] ?? [];
    }

    public function withHeader($name, $value): self
    {
        $clone = clone $this;
        $clone->headers[\strtolower($name)] = \is_array($value) ? $value : [$value];
        return $clone;
    }

    public function withAddedHeader($name, $value): self
    {
        $clone = clone $this;
        $name = \strtolower($name);
        if (!isset($clone->headers[$name])) {
            $clone->headers[$name] = [];
        }
        $clone->headers[$name] = \array_merge($clone->headers[$name], \is_array($value) ? $value : [$value]);
        return $clone;
    }

    public function withoutHeader($name): self
    {
        $clone = clone $this;
        unset($clone->headers[\strtolower($name)]);
        return $clone;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function withBody(mixed $body): self
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        $clone = clone $this;
        $clone->setStatusCode($code, $reasonPhrase);
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function setStatusCode(int $code, string $reasonPhrase = ''): void
    {
        if ($code < 100 || $code > 599) {
            throw new InvalidArgumentException('Invalid HTTP status code');
        }
        $this->statusCode = $code;
        if (empty($reasonPhrase) && isset(self::STANDARD_PHRASES[$code])) {
            $this->reasonPhrase = self::STANDARD_PHRASES[$code];
        } else {
            $this->reasonPhrase = $reasonPhrase;
        }
    }
}
