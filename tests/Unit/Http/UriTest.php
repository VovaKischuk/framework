<?php

namespace Framework\Tests\Unit\Http;

use Framework\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    public function testCanBeConstructedWithUri(): void
    {
        $uri = new Uri('http://user:pass@host:3000/path?query=value#fragment');
        $this->assertInstanceOf(Uri::class, $uri);
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('user:pass', $uri->getUserInfo());
        $this->assertEquals('host', $uri->getHost());
        $this->assertEquals(3000, $uri->getPort());
        $this->assertEquals('/path', $uri->getPath());
        $this->assertEquals('query=value', $uri->getQuery());
        $this->assertEquals('fragment', $uri->getFragment());
    }

    public function testWithSchemeReturnsNewInstanceWithUpdatedScheme(): void
    {
        $uri = new Uri();
        $newUri = $uri->withScheme('https');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('https', $newUri->getScheme());
    }

    public function testWithUserInfoReturnsNewInstanceWithUpdatedUserInfo(): void
    {
        $uri = new Uri();
        $newUri = $uri->withUserInfo('user', 'pass');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('user:pass', $newUri->getUserInfo());
    }

    public function testWithHostReturnsNewInstanceWithUpdatedHost(): void
    {
        $uri = new Uri();
        $newUri = $uri->withHost('example.com');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('example.com', $newUri->getHost());
    }

    public function testWithPortReturnsNewInstanceWithUpdatedPort(): void
    {
        $uri = new Uri();
        $newUri = $uri->withPort(8080);
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals(8080, $newUri->getPort());
    }

    public function testWithPathReturnsNewInstanceWithUpdatedPath(): void
    {
        $uri = new Uri();
        $newUri = $uri->withPath('/new-path');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('/new-path', $newUri->getPath());
    }

    public function testWithQueryReturnsNewInstanceWithUpdatedQuery(): void
    {
        $uri = new Uri();
        $newUri = $uri->withQuery('key=value&key2=value2');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('key=value&key2=value2', $newUri->getQuery());
    }

    public function testWithFragmentReturnsNewInstanceWithUpdatedFragment(): void
    {
        $uri = new Uri();
        $newUri = $uri->withFragment('new-fragment');
        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('new-fragment', $newUri->getFragment());
    }

    public function testToStringOutputsCompleteUri(): void
    {
        $uri = new Uri('http://user:pass@host:3000/path?query=value#fragment');
        $this->assertEquals('http://user:pass@host:3000/path?query=value#fragment', $uri->__toString());
    }
}