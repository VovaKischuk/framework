<?php

namespace Framework\Tests\Unit\Http;

use Framework\Http\Request;
use Psr\Http\Message\UriInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private Request $request;
    private UriInterface $uri;

    protected function setUp(): void
    {
        $this->uri = $this->createMock(UriInterface::class);
        $this->uri->method('getPath')->willReturn('/path');
        $this->uri->method('getQuery')->willReturn('query=123');
        $this->uri->method('getHost')->willReturn('example.com');

        $this->request = new Request('GET', $this->uri);
    }

    public function testGetRequestTargetNoOverride(): void
    {
        $this->assertEquals('/path?query=123', $this->request->getRequestTarget());
    }

    public function testGetRequestTargetWithOverride(): void
    {
        $this->request = $this->request->withRequestTarget('/custom');
        $this->assertEquals('/custom', $this->request->getRequestTarget());
    }

    public function testGetMethod(): void
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    public function testWithMethod(): void
    {
        $newRequest = $this->request->withMethod('post');
        $this->assertNotSame($this->request, $newRequest);
        $this->assertEquals('POST', $newRequest->getMethod());
    }

    public function testGetUri(): void
    {
        $this->assertSame($this->uri, $this->request->getUri());
    }

    public function testUpdateHostFromUri(): void
    {
        $this->assertNull($this->request->getHeader('host'));
    }
}
