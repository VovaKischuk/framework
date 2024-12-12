<?php

namespace Framework\Tests\Unit;

use Framework\Http\Response;
use Framework\Middleware\MiddlewareInterface;
use Framework\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class RouteTest extends TestCase
{
    private Route $router;
    private ServerRequestInterface $request;
    private UriInterface $uri;

    protected function setUp(): void
    {
        $this->router = new Route();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->uri = $this->createMock(UriInterface::class);
    }

    public function testRouteNotFound(): void
    {
        $this->request->method('getMethod')->willReturn('GET');
        $this->request->method('getUri')->willReturn($this->uri);
        $this->uri->method('getPath')->willReturn('/non-existent-path');

        $response = $this->router->dispatch($this->request);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getBody());
    }

    public function testRouteWithMiddlewaresInteraction(): void
    {
        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->method('process')->willReturnCallback(
            function ($request, $next) {
                return $next($request);
            }
        );

        $this->router->addMiddleware($middleware);

        $this->router->addRoute('GET', '/', [$this, 'validRouteCallback']);

        $this->uri->method('getPath')->willReturn('/');
        $this->request->method('getUri')->willReturn($this->uri);
        $this->request->method('getMethod')->willReturn('GET');

        $response = $this->router->dispatch($this->request);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function validRouteCallback(): Response
    {
        return new Response(200, [], "Response from callback");
    }
}