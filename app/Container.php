<?php

namespace Framework;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    public function get($id): mixed
    {
        return $this->services[$id] ?? null;
    }

    public function has($id): bool
    {
        return isset($this->services[$id]);
    }

    public function set($id, $service): void
    {
        $this->services[$id] = $service;
    }
}
