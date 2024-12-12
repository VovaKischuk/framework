<?php

return [
    '/' => [
        'GET' => [Framework\Controller\ExampleController::class, 'testGet', 'middleware' => false],
        'POST' => [Framework\Controller\ExampleController::class, 'testPost', 'middleware' => false],
        'PUT' => [
            Framework\Controller\ExampleController::class,
            'testPut'
        ],
    ],
];
