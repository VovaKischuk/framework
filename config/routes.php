<?php

use Framework\Controller\{AuthController, CartController, ExampleController, ProductController};

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
        'GET' => [CartController::class, 'getCart', 'middleware' => false],
        'POST' => [CartController::class, 'addItem', 'middleware' => false],
        'DELETE' => [CartController::class, 'removeItem', 'middleware' => false],
    ],
    '/auth/login' => [
        'POST' => [AuthController::class, 'login', 'middleware' => false],
    ],
    '/auth/logout' => [
        'POST' => [AuthController::class, 'logout', 'middleware' => false],
    ],
    '/auth/register' => [
        'POST' => [AuthController::class, 'register', 'middleware' => false],
    ],
];
