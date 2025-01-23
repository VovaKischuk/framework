<?php

use Framework\Response\ApiResponse;
use Framework\Http\{ServerRequest, Stream, Uri};
use Framework\Middleware\DefaultMiddleware;
use Framework\Route;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/service_container.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = new Uri($_SERVER['REQUEST_URI'] ?? '/');
$headers = \getallheaders();
$body = new Stream(fopen('php://input', 'r+'));
$request = new ServerRequest($method, $uri, $headers, $body, $_SERVER);

try {
    $router = $container->get(Route::class);
    $router->addMiddleware(new DefaultMiddleware());
    /** @var ApiResponse $response */
    $response = $router->dispatch($request);

    echo $response->getContent();
} catch (\Throwable $e) {
    \header('HTTP/1.1 500 Internal Server Error');
    dd($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
}
