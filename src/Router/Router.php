<?php

declare(strict_types=1);

namespace Framework\Router;

class Router
{
    private array $routes = [];

    public function addRoute(string $uri, string $method, callable $handler): void
    {
        $this->routes[$uri][$method] = $handler;
    }

    public function match(string $uri, string $method): ?array
    {
        return $this->routes[$uri][$method] ?? null;
    }
}
