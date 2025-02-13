<?php

use Framework\Controller\{AuthController, CartController, ExampleController, InventoryController, ProductController};
use Framework\Middleware\{AuthMiddleware, CSRFMiddleware, JWTMiddleware};

$entityManager = require __DIR__ . '/doctrine.php';
$csrfMiddleware = new CSRFMiddleware();
$authMiddleware = new AuthMiddleware();
$jwtMiddleware = new JWTMiddleware($entityManager);

return [
    '/' => [
        'GET' => [ExampleController::class, 'testGet', 'middleware' => false],
        'POST' => [ExampleController::class, 'testPost', 'middleware' => false],
        'PUT' => [ExampleController::class, 'testPut'],
    ],
    '/products' => [
        'GET' => [ProductController::class, 'listProducts', 'middleware' => false],
        'POST' => [ProductController::class, 'createProduct', 'middleware' => false],
    ],
    '/products/{id}' => [
        'GET' => [ProductController::class, 'getProduct', 'middleware' => false],
        'PUT' => [ProductController::class, 'updateProduct', 'middleware' => false],
        'DELETE' => [ProductController::class, 'deleteProduct', 'middleware' => false],
    ],
    'cart' => [
        'GET' => [CartController::class, 'getCart', 'middleware' => [
            $csrfMiddleware,
            $authMiddleware
        ]],
        'POST' => [CartController::class, 'addItem', 'middleware' => [
            $csrfMiddleware,
            $authMiddleware
        ]],
        'DELETE' => [CartController::class, 'removeItem', 'middleware' => [
            $csrfMiddleware,
            $authMiddleware
        ]],
    ],
    '/auth/login' => [
        'POST' => [AuthController::class, 'login', 'middleware' => [$csrfMiddleware]],
    ],
    '/auth/logout' => [
        'POST' => [AuthController::class, 'logout', 'middleware' => false],
    ],
    '/auth/register' => [
        'POST' => [AuthController::class, 'register', 'middleware' => false],
    ],
    '/api/fetch-xml' => [
        'GET' => [ExampleController::class, 'fetchAndParseXML', 'middleware' => [$jwtMiddleware, $authMiddleware]],
    ],
    '/api/inventory' => [
        'POST' => [InventoryController::class, 'create', 'middleware' => [$jwtMiddleware]],
        'GET' => [InventoryController::class, 'list', 'middleware' => [$jwtMiddleware]],
    ],
    '/api/inventory/{id}' => [
        'DELETE' => [InventoryController::class, 'delete', 'middleware' => [$jwtMiddleware]],
        'PUT' => [InventoryController::class, 'update', 'middleware' => [$jwtMiddleware]],
    ],
    '/app-demo' => [
        'GET' => [ExampleController::class, 'appDemo', 'middleware' => false],
    ],
];
