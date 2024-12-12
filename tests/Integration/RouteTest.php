<?php

namespace Framework\Tests\Integration;

use Framework\Controller\ExampleController;
use Framework\Http\ServerRequest;
use PHPUnit\Framework\TestCase;
use Framework\Route;
use Framework\Http\Uri;

class RouteTest extends TestCase
{
    private Route $route;

    protected function setUp(): void
    {
        $this->route = new Route();
    }

    public function testHomepageReturnsCorrectResponse(): void
    {
        $this->route->addRoute('GET', '/', [ExampleController::class, 'testGet']);

        $request = new ServerRequest('GET', new Uri('/'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Get response', (string)$response->getBody());
    }

    public function testNotFoundResponse(): void
    {
        $request = new ServerRequest('GET', new Uri('/not-found'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', (string)$response->getBody());
    }

    public function testMethodNotAllowed(): void
    {
        $this->route->addRoute('GET', '/data', [ExampleController::class, 'testGet']);

        $request = new ServerRequest('POST', new Uri('/data'));
        $response = $this->route->dispatch($request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}