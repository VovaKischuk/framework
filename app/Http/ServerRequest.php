<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\UploadedFileInterface;
use InvalidArgumentException;

class ServerRequest extends Request implements ServerRequestInterface
{
    private array $attributes = [];

    public function __construct(
        protected string       $method,
        protected UriInterface $uri,
        protected array        $headers = [],
        protected mixed        $body = null,
        private readonly array $serverParams = [],
        private array          $cookieParams = [],
        private array          $queryParams = [],
        private array          $uploadedFiles = [],
        private mixed          $parsedBody = null,
        protected string       $protocolVersion = '1.1'
    )
    {
        parent::__construct($method, $uri, $body, $headers, $protocolVersion);
        $this->setUploadedFiles($uploadedFiles);
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): self
    {
        $clone = clone $this;
        $clone->cookieParams = $cookies;
        return $clone;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $clone = clone $this;
        $clone->queryParams = $query;
        return $clone;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): self
    {
        $clone = clone $this;
        $clone->setUploadedFiles($uploadedFiles);
        return $clone;
    }

    private function setUploadedFiles(array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $file) {
            if (!$file instanceof UploadedFileInterface) {
                throw new InvalidArgumentException('Each uploaded file must implement UploadedFileInterface.');
            }
        }
        $this->uploadedFiles = $uploadedFiles;
    }

    public function getParsedBody(): mixed
    {
        return $this->parsedBody;
    }

    public function withParsedBody(mixed $data): self
    {
        if (!is_array($data) && !is_object($data) && $data !== null) {
            throw new InvalidArgumentException('Parsed body must be an array, object, or null.');
        }

        $clone = clone $this;
        $clone->parsedBody = $data;
        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, mixed $value): self
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    public function withoutAttribute(string $name): self
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }
}