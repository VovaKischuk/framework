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

    public function fetchAndParseXML(): ApiResponse
    {
        //TODO: resource is not available
        $url = 'https://thetestrequest.com/authors';
        return ApiResponse::fromPayload(['url' => $url]);
    }
}
