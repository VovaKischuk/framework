<?php

namespace Framework\Tests\Unit\Controller;

use Framework\Controller\ExampleController;
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
        $this->assertEquals('{"message":"Get response"}', $response->getContent());
    }

    public function testPostResponse(): void
    {
        $response = $this->controller->testPost();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"Post response"}', $response->getContent());
    }

    public function testPutResponse(): void
    {
        $response = $this->controller->testPut();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"Put response"}', $response->getContent());
    }
}
