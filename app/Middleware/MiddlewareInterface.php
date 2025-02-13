<?php

namespace Framework\Middleware;

use Framework\Response\ApiResponse;
use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, callable $next): ApiResponse;
}
