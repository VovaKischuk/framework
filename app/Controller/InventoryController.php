<?php

namespace Framework\Controller;

use Doctrine\ORM\EntityManager;
use Framework\Entity\Inventory;
use Framework\Http\Request;
use Framework\Response\ApiResponse;

class InventoryController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Request $request): ApiResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['name'], $data['quantity'])) {
            return ApiResponse::fromPayload(['error' => 'Name and quantity are required'], 400);
        }

        $inventory = new Inventory();
        $inventory->setName($data['name']);
        $inventory->setQuantity($data['quantity']);

        $this->entityManager->persist($inventory);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Inventory item created'], 201);
    }

    public function list(): ApiResponse
    {
        $inventoryRepository = $this->entityManager->getRepository(Inventory::class);
        $items = $inventoryRepository->findAll();

        return ApiResponse::fromPayload($items, 200);
    }

    public function delete(int $id): ApiResponse
    {
        $inventoryRepository = $this->entityManager->getRepository(Inventory::class);
        $item = $inventoryRepository->find($id);

        if (!$item) {
            return ApiResponse::fromPayload(['error' => 'Item not found'], 404);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Item deleted'], 200);
    }

    public function update(int $id, Request $request): ApiResponse
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['quantity'])) {
            return ApiResponse::fromPayload(['error' => 'Quantity is required'], 400);
        }

        $inventoryRepository = $this->entityManager->getRepository(Inventory::class);
        $item = $inventoryRepository->find($id);

        if (!$item) {
            return ApiResponse::fromPayload(['error' => 'Item not found'], 404);
        }

        $item->setQuantity($data['quantity']);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Item updated'], 200);
    }
}
