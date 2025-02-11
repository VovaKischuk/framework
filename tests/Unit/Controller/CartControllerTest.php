<?php

namespace Framework\Tests\Unit\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Framework\Controller\CartController;
use Framework\Entity\CartItem;
use Framework\Entity\Product;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Framework\Http\Request;

class CartControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->cartItemRepository = $this->createMock(EntityRepository::class);
        $this->entityManager->method('getRepository')->willReturn($this->cartItemRepository);
        $this->controller = new CartController($this->entityManager);
    }

    public function testListCartItemsReturnsEmptyCartWhenNoItems(): void
    {
        $this->cartItemRepository->method('findAll')->willReturn([]);

        $response = $this->controller->listCartItems();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['cart' => []], json_decode($response->getContent(), true));
    }

    public function testAddItemUpdatesExistingItemInCart(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode(['productId' => 1, 'quantity' => 2]));

        $product = $this->createMock(Product::class);
        $this->entityManager->method('find')->willReturn($product);

        $existingItem = $this->createMock(CartItem::class);
        $existingItem->method('getQuantity')->willReturn(1);

        $cartItemRepository = $this->createMock(EntityRepository::class);
        $cartItemRepository->method('findOneBy')->willReturn($existingItem);
        $this->entityManager->method('getRepository')->willReturn($cartItemRepository);

        $response = $this->controller->addItem($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Item added to cart'], json_decode($response->getContent(), true));
    }

    public function testAddItemReturnsErrorWhenProductIdOrQuantityIsMissing(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode(['productId' => 1]));

        $response = $this->controller->addItem($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Product ID and quantity are required'], json_decode($response->getContent(), true));
    }

    public function testAddItemReturnsErrorWhenProductNotFound(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode(['productId' => 1, 'quantity' => 2]));

        $this->entityManager->method('find')->willReturn(null);

        $response = $this->controller->addItem($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Product not found'], json_decode($response->getContent(), true));
    }

    public function testRemoveItemRemovesItemFromCart(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode(['productId' => 1]));

        $cartItem = $this->createMock(CartItem::class);
        $this->cartItemRepository->method('findOneBy')->with(['productId' => 1])->willReturn($cartItem);

        $this->entityManager->expects($this->once())->method('remove')->with($cartItem);
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->removeItem($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Item removed from cart'], json_decode($response->getContent(), true));
    }

    public function testRemoveItemReturnsErrorWhenProductIdIsMissing(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode([]));

        $response = $this->controller->removeItem($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Product ID is required'], json_decode($response->getContent(), true));
    }

    public function testRemoveItemReturnsErrorWhenItemNotFound(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($this->createMock(StreamInterface::class));
        $request->getBody()->method('getContents')->willReturn(json_encode(['productId' => 1]));

        $this->cartItemRepository->method('findOneBy')->with(['productId' => 1])->willReturn(null);

        $response = $this->controller->removeItem($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Item not found in cart'], json_decode($response->getContent(), true));
    }
}