<?php

namespace Framework;

use Doctrine\ORM\EntityManager;
use Framework\Middleware\MiddlewareInterface;
use Framework\Response\ApiResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class Route
{
    private array $routes = [];
    private array $middleware = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function addRoute($method, $path, $handler): void
    {
        $this->routes[$path][$method] = $handler;
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function dispatch(Request $request): ApiResponse
    {
        try {
            $method = $request->getMethod();
            $path = $request->getUri()->getPath();

            foreach ($this->routes as $routePath => $methods) {
                $params = [];

                if ($this->matchRoute($routePath, $path, $params)) {
                    if (!isset($methods[$method])) {
                        return ApiResponse::empty(404);
                    }

                    foreach ($params as $key => $value) {
                        $request = $request->withAttribute($key, $value);
                    }

                    if (
                        isset($methods[$method]['middleware'])
                        && $methods[$method]['middleware'] === false
                    ) {
                        return $this->sendResponse($request);
                    }

                    return $this->processMiddleware($request, function ($request) {
                        return $this->sendResponse($request);
                    });
                }
            }

            return ApiResponse::empty(404);
        } catch (\Throwable $exception) {
            dd($exception->getMessage() . ' ' . $exception->getLine());
        }
    }

    private function processMiddleware(Request $request, callable $core): ApiResponse
    {
        $layer = array_reduce(
            array_reverse($this->middleware),
            static function ($next, $middleware) {
                return static function ($request) use ($next, $middleware) {
                    return $middleware->process($request, $next);
                };
            },
            $core
        );

        return $layer($request);
    }

    private function sendResponse(Request $request): ApiResponse
    {
        try {
            $method = $request->getMethod();
            $path = $request->getUri()->getPath();
            $matchedRoute = null;
            $params = [];

            foreach ($this->routes as $routePath => $methods) {
                if ($this->matchRoute($routePath, $path, $params)) {
                    if (!isset($methods[$method])) {
                        throw new \Exception("Method not allowed for this route.");
                    }

                    $matchedRoute = $methods[$method];
                    break;
                }
            }

            if (!$matchedRoute) {
                throw new \Exception("No route found for the requested path.");
            }

            [$controller, $actionMethod] = $matchedRoute;
            $controller = new $controller($this->container->get(EntityManager::class));

            return $controller->$actionMethod($request, $params);
        } catch (\Throwable $exception) {
            dd($exception->getMessage() . ' ' . $exception->getLine() . ' ' . $exception->getFile());
        }
    }

    private function matchRoute(string $routePath, string $requestPath, ?array &$params = []): bool
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $pathParts = explode('/', trim($requestPath, '/'));

        if (count($routeParts) !== count($pathParts)) {
            return false;
        }

        $params = [];

        foreach ($routeParts as $index => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $pathParts[$index];
            } elseif ($part !== $pathParts[$index]) {
                return false;
            }
        }

        return true;
    }
}
