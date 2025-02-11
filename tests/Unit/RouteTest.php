<?php

namespace Framework\Tests\Unit;

use Framework\Http\Response;
use Framework\Middleware\MiddlewareInterface;
use Framework\Response\ApiResponse;
use Framework\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RouteTest extends TestCase
{
    private Route $router;
    private ServerRequestInterface $request;
    private UriInterface $uri;

    protected function setUp(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $this->router = new Route($container);
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
    }
}