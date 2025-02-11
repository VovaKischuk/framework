<?php

namespace Framework\Controller;

use Framework\Response\ApiResponse;

readonly class ExampleController
{
    public function testGet(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Get response']);
    }

    public function testPost(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Post response']);
    }

    public function testPut(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Put response']);
    }
}
