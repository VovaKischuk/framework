<?php

declare(strict_types=1);

namespace Framework\Controller;

use Framework\Response\ApiResponse;
use Framework\Http\Request;
use Doctrine\ORM\EntityManager;
use Framework\Entity\User;

class AuthController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function login(Request $request): ApiResponse
    {
        parse_str($request->getBody()->getContents(), $data);

        if (!isset($data['username'], $data['password'])) {
            return ApiResponse::fromPayload(['error' => 'Username and password are required'], 400);
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => $data['username']]);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            return ApiResponse::fromPayload(['error' => 'Invalid username or password'], 401);
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role'] = $user->getRole();

        return ApiResponse::fromPayload(['message' => 'Login successful'], 200);
    }

    public function logout(): ApiResponse
    {
        session_destroy();
        return ApiResponse::fromPayload(['message' => 'Logout successful'], 200);
    }

    public function register(Request $request): ApiResponse
    {
        parse_str($request->getBody()->getContents(), $data);

        if (!isset($data['username'], $data['password'], $data['role'])) {
            return ApiResponse::fromPayload(['error' => 'All fields are required'], 400);
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        if ($userRepository->findOneBy(['username' => $data['username']])) {
            return ApiResponse::fromPayload(['error' => 'Username already exists'], 400);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setRole($data['role']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ApiResponse::fromPayload(['message' => 'Registration successful'], 201);
    }
}
