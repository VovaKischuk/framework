<?php

namespace Framework\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    private ?string $requestTarget = null;

    public function __construct(
        private string $method,
        private UriInterface $uri,
        protected mixed $body = null,
        protected array $headers = [],
        protected string $protocolVersion = '1.1'
    ) {
        parent::__construct($body, $protocolVersion, $headers);
        $this->updateHostFromUri();
    }

    public function getRequestTarget(): string
    {
        if (!empty($this->requestTarget)) {
            return $this->requestTarget;
        }

        if (empty($this->uri->getPath())) {
            return "/";
        }

        $target = $this->uri->getPath();
        if (!empty($this->uri->getQuery())) {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    public function withRequestTarget($requestTarget): self
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): self
    {
        $clone = clone $this;
        $clone->method = \strtoupper($method);

        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if ($preserveHost && empty($uri->getHost())) {
            return $clone;
        }

        $clone->updateHostFromUri();

        return $clone;
    }

    private function updateHostFromUri(): void
    {
        $host = $this->uri->getHost();
        if (!empty($host)) {
            $this->headers['host'] = [$host];
        }
    }
}
