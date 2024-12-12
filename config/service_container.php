<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Container;
use Framework\Route;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$container = new Container();

$logger = new Logger('default_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));
$container->set(LoggerInterface::class, $logger);

$routing = new Route();
$routes = require __DIR__ . '/routes.php';
foreach ($routes as $path => $route) {
    foreach ($route as $method => $handler) {
        $routing->addRoute($method, $path, $handler);
    }
}
$container->set(Route::class, $routing);

return $container;