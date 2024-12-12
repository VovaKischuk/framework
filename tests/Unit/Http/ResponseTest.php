<?php

namespace Framework\Tests\Unit\Http;

use Framework\Http\Response;
use Psr\Http\Message\StreamInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testConstructorAssignsValuesCorrectly(): void
    {
        $bodyContent = 'Body Test';
        $headers = ['X-Test' => ['header value']];
        $response = new Response(201, $headers, $bodyContent, 'Created', '2.0');

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals($headers, $response->getHeaders());
        $this->assertEquals($bodyContent, $response->getBody());
        $this->assertEquals('2.0', $response->getProtocolVersion());
    }

    public function testWithStatus(): void
    {
        $response = new Response();
        $newResponse = $response->withStatus(404, 'Not Found');

        $this->assertNotSame($response, $newResponse);
        $this->assertEquals(404, $newResponse->getStatusCode());
        $this->assertTrue($newResponse->hasHeader('X-Custom-Header') === false);
        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals('Not Found', $newResponse->getReasonPhrase());
    }

    public function testGetReasonPhraseReturnsStandardPhraseIfEmpty(): void
    {
        $response = new Response(404);
        $this->assertEquals('Not Found', $response->getReasonPhrase());
    }

    public function testImmutabilityWithHeaders(): void
    {
        $response = new Response();
        $response2 = $response->withHeader('Content-Type', 'application/json');

        $this->assertNotSame($response, $response2);
        $this->assertFalse($response->hasHeader('Content-Type'));
        $this->assertTrue($response2->hasHeader('Content-Type'));
    }

    public function testImmutabilityWithAddedHeader(): void
    {
        $response = new Response();
        $newResponse = $response->withAddedHeader('X-Custom', 'ValueA');

        $this->assertNotSame($response, $newResponse);
        $this->assertTrue($newResponse->hasHeader('X-Custom'));
        $this->assertEquals(['ValueA'], $newResponse->getHeader('X-Custom'));

        $yetAnotherResponse = $newResponse->withAddedHeader('X-Custom', 'ValueB');
        $this->assertEquals(['ValueA', 'ValueB'], $yetAnotherResponse->getHeader('X-Custom'));
    }

    public function testImmutabilityWithBody(): void
    {
        $response = new Response();
        $streamMock = $this->createMock(StreamInterface::class);
        $newResponse = $response->withBody($streamMock);

        $this->assertNotSame($response, $newResponse);
        $this->assertSame($streamMock, $newResponse->getBody());
    }

    public function testImmutabilityWithProtocolVersion(): void
    {
        $response = new Response();
        $newResponse = $response->withProtocolVersion('2.0');

        $this->assertNotSame($response, $newResponse);
        $this->assertEquals('2.0', $newResponse->getProtocolVersion());
    }

    public function testWithoutHeader(): void
    {
        $response = new Response();
        $modifiedResponse = $response->withAddedHeader('X-Custom', 'Value');
        $this->assertTrue($modifiedResponse->hasHeader('X-Custom'));

        $finalResponse = $modifiedResponse->withoutHeader('X-Custom');
        $this->assertFalse($finalResponse->hasHeader('X-Custom'));
    }
}