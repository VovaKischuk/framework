<?php

namespace Framework\Controller;

use Doctrine\ORM\EntityManager;
use Framework\Entity\Product;
use Framework\Response\ApiResponse;
use Framework\Transformer\ProductTransformer;
use Framework\Http\Request;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {
    }

    public function listProducts(): ApiResponse
    {
        try {
            $productRepository = $this->entityManager->getRepository(Product::class);
            $products = $productRepository->findAll();

            $resource = $this->collection($products, new ProductTransformer());
        } catch (\Exception $e) {
            return ApiResponse::fromPayload(['error' => $e->getMessage()], 500);
        }

        return ApiResponse::fromPayload($resource);
    }

    public function createProduct(Request $request): ApiResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['name'], $data['quantity'], $data['price'])) {
            return ApiResponse::fromPayload(['error' => 'Invalid input'], 400);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setQuantity($data['quantity']);
        $product->setPrice($data['price']);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $resource = $this->item($product, new ProductTransformer());
        return ApiResponse::fromPayload($resource);
    }

    public function getProduct(Request $request, array $params): ApiResponse
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            return ApiResponse::fromPayload(['error' => 'Product ID is required'], 400);
        }

        $product = $this->entityManager->find(Product::class, $id);

        if (!$product) {
            return ApiResponse::fromPayload(['error' => 'Product not found'], 404);
        }

        $resource = $this->item($product, new ProductTransformer());
        return ApiResponse::fromPayload($resource);
    }

    public function updateProduct(Request $request, array $params): ApiResponse
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            return ApiResponse::fromPayload(['error' => 'Product ID is required'], 400);
        }

        $product = $this->entityManager->find(Product::class, $id);

        if (!$product) {
            return ApiResponse::fromPayload(['error' => 'Product not found'], 404);
        }

        $data = json_decode($request->getBody()->getContents(), true);

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }

        if (isset($data['quantity'])) {
            $product->setQuantity($data['quantity']);
        }

        if (isset($data['price'])) {
            $product->setPrice($data['price']);
        }

        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Product updated successfully'], 200);
    }

    public function deleteProduct(Request $request, array $params): ApiResponse
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            return ApiResponse::fromPayload(['error' => 'Product ID is required'], 400);
        }

        $product = $this->entityManager->find(Product::class, $id);

        if (!$product) {
            return ApiResponse::fromPayload(['error' => 'Product not found'], 404);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Product deleted successfully'], 200);
    }
}
