<?php

namespace Framework\Controller;

use Doctrine\ORM\EntityManager;
use Framework\Entity\CartItem;
use Framework\Entity\Product;
use Framework\Response\ApiResponse;
use Framework\Response\HtmlResponse;
use Framework\Template\TemplateEngine;
use Framework\Http\Response;

readonly class ExampleController
{
    public function __construct(
        private ?EntityManager $entityManager,
        private TemplateEngine $templateEngine = new TemplateEngine(__DIR__ . '/../../templates')
    ) {
    }

    public function appDemo(): Response
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $items = $cartItemRepository->findAll();

        $html = $this->templateEngine->render('app-demo', [
            'csrfToken' => $_SESSION['csrf_token'] ?? '',
            'products' => $products,
            'user' => $_SESSION['user'],
            'cartItems' => $items
        ]);

        return HtmlResponse::html($html);
    }

    public function testGet(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Get response']);
    }

    public function testPost(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Post response']);
    }

    public function testPut(): ApiResponse
    {
        return ApiResponse::fromPayload(['message' => 'Put response']);
    }

    public function fetchAndParseXML(): ApiResponse
    {
        //TODO: resource is not available
        $url = 'https://thetestrequest.com/authors';
        return ApiResponse::fromPayload(['url' => $url]);
    }
}
