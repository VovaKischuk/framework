<?php

namespace Framework\Http;

use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

class Uri implements UriInterface
{
    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(string $uri = '')
    {
        if (!empty($uri)) {
            $parts = \parse_url($uri);
            if ($parts === false) {
                throw new InvalidArgumentException("Invalid URI: $uri");
            }

            $this->scheme = $parts['scheme'] ?? '';
            $this->userInfo = $parts['user'] ?? '';
            $this->host = $parts['host'] ?? '';
            $this->port = $parts['port'] ?? null;
            $this->path = $parts['path'] ?? '';
            $this->query = $parts['query'] ?? '';
            $this->fragment = $parts['fragment'] ?? '';

            if (isset($parts['pass'])) {
                $this->userInfo .= ':' . $parts['pass'];
            }
        }
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->host;
        if (!empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;
        }
        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): UriInterface
    {
        $new = clone $this;
        $new->scheme = \strtolower($scheme);

        return $new;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $new = clone $this;
        $new->userInfo = $user . ($password !== null ? ":$password" : '');

        return $new;
    }

    public function withHost(string $host): UriInterface
    {
        $new = clone $this;
        $new->host = \strtolower($host);

        return $new;
    }

    public function withPort(?int $port): UriInterface
    {
        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    public function withPath(string $path): UriInterface
    {
        if (!\preg_match('/^(\/?|([^\/]*:[^\/]*@)?[^\/]+(:\d+)?\/)(.*)$/', $path)) {
            throw new InvalidArgumentException("Invalid path: $path");
        }
        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    public function withQuery(string $query): UriInterface
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function withFragment(string $fragment): UriInterface
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        return $this->composeUri();
    }

    private function composeUri(): string
    {
        return (!empty($this->scheme) ? $this->scheme . '://' : '') .
            $this->getAuthority() .
            $this->path .
            (!empty($this->query) ? '?' . $this->query : '') .
            (!empty($this->fragment) ? '#' . $this->fragment : '');
    }
}
