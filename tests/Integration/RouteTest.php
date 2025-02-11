<?php

namespace Framework\Tests\Integration;

use Framework\Controller\ExampleController;
use Framework\Http\ServerRequest;
use PHPUnit\Framework\TestCase;
use Framework\Route;
use Framework\Http\Uri;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RouteTest extends TestCase
{
    private Route $route;

    protected function setUp(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $this->route = new Route($container);
    }

    public function testHomepageReturnsCorrectResponse(): void
    {
        $this->route->addRoute('GET', '/', [ExampleController::class, 'testGet']);

        $request = new ServerRequest('GET', new Uri('/'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"Get response"}', $response->getContent());
    }

    public function testNotFoundResponse(): void
    {
        $request = new ServerRequest('GET', new Uri('/not-found'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testMethodNotAllowed(): void
    {
        $this->route->addRoute('GET', '/data', [ExampleController::class, 'testGet']);

        $request = new ServerRequest('POST', new Uri('/data'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}