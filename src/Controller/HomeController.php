<?php

declare(strict_types=1);

namespace Framework\Controller;

use Psr\Http\Message\{RequestInterface, ResponseInterface};

class HomeController
{
    public function index(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Hello, World!');

        return $response;
    }

    public function about(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('About Us');

        return $response;
    }

    public function contact(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Contact Us');

        return $response;
    }
}
