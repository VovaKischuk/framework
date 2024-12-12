<?php

namespace Framework\Tests\Unit;

use Framework\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testSetAndGetService(): void
    {
        $service = new \stdClass;
        $serviceId = 'example.service';

        $this->container->set($serviceId, $service);

        $retrievedService = $this->container->get($serviceId);
        $this->assertSame($service, $retrievedService);
    }

    public function testGetNonExistentService(): void
    {
        $nonExistentServiceId = 'nonexistent.service';
        $retrievedService = $this->container->get($nonExistentServiceId);

        $this->assertNull($retrievedService);
    }

    public function testHasService(): void
    {
        $service = new \stdClass;
        $serviceId = 'example.service';

        $this->assertFalse($this->container->has($serviceId));

        $this->container->set($serviceId, $service);

        $this->assertTrue($this->container->has($serviceId));
    }
}