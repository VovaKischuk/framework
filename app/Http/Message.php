<?php

namespace Framework\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    public function __construct(
        private mixed $body = null,
        private string $protocolVersion = '1.1',
        private array $headers = []
    ) {
        $this->setHeadersToLowerCase();
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): self
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return array_key_exists(\strtolower($name), $this->headers);
    }

    public function getHeader(string $name): mixed
    {
        return $this->headers[\strtolower($name)] ?? null;
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, mixed $value): self
    {
        $value = is_array($value) ? $value : [$value];

        $new = clone $this;
        $new->headers[\strtolower($name)] = $value;

        return $new;
    }

    public function withAddedHeader(string $name, mixed $value): self
    {
        $value = is_array($value) ? $value : [$value];

        $new = clone $this;
        $name = \strtolower($name);

        if ($new->hasHeader($name)) {
            $new->headers[$name] = \array_merge($new->headers[$name], $value);
        } else {
            $new->headers[$name] = $value;
        }

        return $new;
    }

    public function withoutHeader(string $name): self
    {
        $new = clone $this;
        unset($new->headers[\strtolower($name)]);

        return $new;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    private function setHeadersToLowerCase(): void
    {
        $this->headers = array_change_key_case($this->headers, CASE_LOWER);
    }
}
