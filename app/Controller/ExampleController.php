<?php

namespace Framework\Controller;

use Framework\Http\Response;

readonly class ExampleController
{
    public function testGet(): Response
    {
        return new Response(200, [], 'Get response');
    }

    public function testPost(): Response
    {
        return new Response(200, [], 'Post response');
    }

    public function testPut(): Response
    {
        return new Response(200, [], 'Put response');
    }
}
