<?php

namespace Framework\Controller;

use Doctrine\ORM\EntityManager;
use Framework\Entity\CartItem;
use Framework\Entity\Product;
use Framework\Response\ApiResponse;
use Framework\Http\Request;

class CartController extends AbstractController
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function addItem(Request $request): ApiResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['productId'], $data['quantity'])) {
            return ApiResponse::fromPayload(['error' => 'Product ID and quantity are required'], 400);
        }

        $productId = $data['productId'];
        $quantity = $data['quantity'];

        $product = $this->entityManager->find(Product::class, $productId);
        if (!$product) {
            return ApiResponse::fromPayload(['error' => 'Product not found'], 404);
        }

        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $existingItem = $cartItemRepository->findOneBy(['productId' => $productId]);

        if ($existingItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $this->entityManager->persist($cartItem);
        }

        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Item added to cart'], 201);
    }

    public function removeItem(Request $request): ApiResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['productId'])) {
            return ApiResponse::fromPayload(['error' => 'Product ID is required'], 400);
        }

        $productId = $data['productId'];

        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $cartItem = $cartItemRepository->findOneBy(['productId' => $productId]);

        if (!$cartItem) {
            return ApiResponse::fromPayload(['error' => 'Item not found in cart'], 404);
        }

        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Item removed from cart'], 200);
    }

    public function listCartItems(): ApiResponse
    {
        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $items = $cartItemRepository->findAll();

        $cart = array_map(function (CartItem $item) {
            return [
                'productId' => $item->getProductId(),
                'quantity' => $item->getQuantity(),
            ];
        }, $items);

        return ApiResponse::fromPayload(['cart' => $cart], 200);
    }
}
