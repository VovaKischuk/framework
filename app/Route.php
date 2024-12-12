<?php

namespace Framework;

use Framework\Middleware\MiddlewareInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Http\Response;

class Route
{
    private array $routes = [];
    private array $middleware = [];

    public function __construct()
    {
    }

    public function addRoute($method, $path, $handler): void
    {
        $this->routes[$path][$method] = $handler;
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        if (!isset($this->routes[$path][$method])) {
            return new Response(404, [], 'Not Found');
        }

        if (
            isset($this->routes[$path][$method]['middleware'])
            && $this->routes[$path][$method]['middleware'] === false
        ) {
            return $this->sendResponse($request);
        }

        return $this->processMiddleware($request, function ($request) {
            return $this->sendResponse($request);
        });
    }

    private function processMiddleware(Request $request, callable $core): Response
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

    private function sendResponse(Request $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        [$controller, $method] = $this->routes[$path][$method];
        $controller = new $controller();

        return $controller->$method($request);
    }
}
