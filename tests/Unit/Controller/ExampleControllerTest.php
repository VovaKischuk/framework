<?php

namespace Framework\Tests\Unit\Controller;

use Framework\Controller\ExampleController;
use Framework\Http\Response;
use PHPUnit\Framework\TestCase;

class ExampleControllerTest extends TestCase
{
    private ExampleController $controller;

    protected function setUp(): void
    {
        $this->controller = new ExampleController();
    }

    public function testGetResponse(): void
    {
        $response = $this->controller->testGet();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Get response', $response->getBody());
    }

    public function testPostResponse(): void
    {
        $response = $this->controller->testPost();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Post response', $response->getBody());
    }

    public function testPutResponse(): void
    {
        $response = $this->controller->testPut();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Put response', $response->getBody());
    }
}
