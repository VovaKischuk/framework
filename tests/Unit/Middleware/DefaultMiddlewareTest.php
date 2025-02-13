<?php

namespace Framework\Tests\Unit\Middleware;

use Framework\Http\Response;
use Framework\Middleware\DefaultMiddleware;
use Framework\Response\ApiResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class DefaultMiddlewareTest extends TestCase
{
    public function testProcessContinuesOnValidHeader(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $middleware = new DefaultMiddleware();

        $request->method('getHeader')->willReturn(['1']);
        $next = function ($request) {
            return ApiResponse::fromPayload(['error' => 'Request proceeded'], 200);
        };

        $response = $middleware->process($request, $next);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['error' => 'Request proceeded'], json_decode($response->getContent(), true));
    }

    public function testProcessStopsOnInvalidHeader(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $middleware = new DefaultMiddleware();

        $request->method('getHeader')->willReturn([]);
        $next = function ($request) {
            return ApiResponse::fromPayload(['message' => 'Request should not proceed'], 200);
        };

        $response = $middleware->process($request, $next);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['error' => 'Forbidden'], json_decode($response->getContent(), true));
    }
}