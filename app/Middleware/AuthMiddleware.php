<?php

namespace Framework\Middleware;

use Framework\Http\Request;
use Framework\Response\ApiResponse;

class AuthMiddleware
{
    public function __invoke(Request $request, callable $next, ?array $requiredRoles = null): ApiResponse
    {
        if (!isset($_SESSION['user_id'])) {
            return ApiResponse::fromPayload(['error' => 'Unauthorized'], 401);
        }

        if ($requiredRoles && !in_array($_SESSION['role'], $requiredRoles)) {
            return ApiResponse::fromPayload(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}