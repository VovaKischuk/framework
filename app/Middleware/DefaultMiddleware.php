<?php

namespace Framework\Middleware;

use Framework\Http\Response;
use Framework\Response\ApiResponse;
use Psr\Http\Message\ServerRequestInterface;

class DefaultMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, callable $next): ApiResponse
    {
        $proceed = $request->getHeader('proceed');
        if ($proceed) {
            return $next($request);
        }

        return ApiResponse::fromPayload(['error' => 'Forbidden'], 403);
    }
}
