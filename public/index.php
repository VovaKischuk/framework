<?php

use Framework\Router\Router;
use Framework\Controller\HomeController;
use GuzzleHttp\Psr7\{Response, ServerRequest};

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->addRoute('/', 'GET', [new HomeController(), 'index']);
$router->addRoute('/about', 'GET', [new HomeController(), 'about']);
$router->addRoute('/contact', 'GET', [new HomeController(), 'contact']);

$request = ServerRequest::fromGlobals();
$response = new Response();

$handler = $router->match($request->getUri()->getPath(), $request->getMethod());

if ($handler) {
    $response = call_user_func($handler, $request, $response);
} else {
    $response->getBody()->write('404 Not Found');
}

(new Response())->getBody()->write($response->getBody());
echo $response->getBody();
