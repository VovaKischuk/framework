<?php

namespace Framework\Tests\Unit\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Framework\Controller\ProductController;
use Framework\Entity\Product;
use Framework\Http\Request;
use Framework\Response\ApiResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class ProductControllerTest extends TestCase
{
    private $entityManager;
    private $productRepository;
    private $controller;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->productRepository = $this->createMock(EntityRepository::class);
        $this->entityManager->method('getRepository')->willReturn($this->productRepository);
        $this->controller = new ProductController($this->entityManager);
    }

    public function testListProducts(): void
    {
        $products = [$this->createMock(Product::class), $this->createMock(Product::class)];
        $this->productRepository->method('findAll')->willReturn($products);

        $response = $this->controller->listProducts();

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateProductCreatesNewProduct(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode([
            'name' => 'New Product',
            'quantity' => 10,
            'price' => 100
        ]));

        $product = new Product();
        $product->setName('New Product');
        $product->setQuantity(10);
        $product->setPrice(100);

        $this->entityManager->expects($this->once())->method('persist')->with($this->callback(function($product) {
            return $product instanceof Product && $product->getName() === 'New Product';
        }));
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->createProduct($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty(json_decode($response->getContent(), true));
    }

    public function testGetProductNotFound(): void
    {
        $request = $this->createMock(Request::class);
        $this->entityManager->method('find')->with(Product::class, 1)->willReturn(null);

        $response = $this->controller->getProduct($request, ['id' => 1]);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateProduct(): void
    {
        $product = $this->createMock(Product::class);
        $request = $this->createMock(Request::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn(json_encode(['name' => 'Updated Product']));
        $request->method('getBody')->willReturn($stream);

        $this->entityManager->method('find')->with(Product::class, 1)->willReturn($product);
        $product->expects($this->once())->method('setName')->with('Updated Product');
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->updateProduct($request, ['id' => 1]);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteProduct(): void
    {
        $product = $this->createMock(Product::class);
        $request = $this->createMock(Request::class);

        $this->entityManager->method('find')->with(Product::class, 1)->willReturn($product);
        $this->entityManager->expects($this->once())->method('remove')->with($product);
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->deleteProduct($request, ['id' => 1]);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
