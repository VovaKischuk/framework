<?php

namespace Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, callable $next): ResponseInterface;
}