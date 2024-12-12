<?php

namespace Framework\Tests\Unit\Http;

use Framework\Http\Message;
use Psr\Http\Message\StreamInterface;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testProtocolVersionHandling(): void
    {
        $message = new Message();

        $this->assertEquals('1.1', $message->getProtocolVersion());

        $message2 = $message->withProtocolVersion('2.0');
        $this->assertEquals('2.0', $message2->getProtocolVersion());
        $this->assertNotSame($message2, $message);
    }

    public function testHeaderManipulations(): void
    {
        $message = new Message();
        $message = $message->withHeader('Content-Type', 'text/plain');

        $this->assertTrue($message->hasHeader('content-type'));
        $this->assertEquals(['text/plain'], $message->getHeader('Content-Type'));

        $message = $message->withAddedHeader('Content-Type', 'application/json');
        $this->assertEquals(['text/plain', 'application/json'], $message->getHeader('content-type'));

        $message = $message->withoutHeader('Content-Type');
        $this->assertFalse($message->hasHeader('Content-Type'));
    }

    public function testBodyHandling(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $message = new Message(body: 'Initial body');

        $this->assertEquals('Initial body', $message->getBody());

        $message2 = $message->withBody($body);
        $this->assertSame($body, $message2->getBody());
        $this->assertNotSame($message2, $message);
    }

    public function testGetHeadersReturnsAllSetHeadersCorrectly(): void
    {
        $message = new Message();
        $message = $message->withHeader('X-Test-Header', 'Test value');
        $this->assertArrayHasKey('x-test-header', $message->getHeaders());
        $this->assertEquals('Test value', implode(', ', $message->getHeaders()['x-test-header']));
    }

    public function testGetHeaderLineConcatenatesValuesCorrectly(): void
    {
        $message = new Message();
        $message = $message->withHeader('Accept', 'application/json')
            ->withAddedHeader('Accept', 'text/plain');

        $this->assertEquals('application/json, text/plain', $message->getHeaderLine('Accept'));
    }
}
