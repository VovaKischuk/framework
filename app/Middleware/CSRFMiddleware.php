<?php

namespace Framework\Middleware;

use Framework\Response\ApiResponse;
use Psr\Http\Message\ServerRequestInterface;

class CSRFMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, callable $next): ApiResponse
    {
        $requestData = $request->getParsedBody();
        $csrfToken = $requestData['csrf_token'] ?? '';
        if (!$csrfToken || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
            return ApiResponse::fromPayload(['error' => 'Invalid CSRF token'], 403);
        }

        return $next($request);
    }
}
