<?php

namespace Framework\Middleware;

use Framework\Entity\User;
use Firebase\JWT\{Key, JWT};
use Framework\Response\ApiResponse;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\ORM\EntityManager;

class JWTMiddleware implements MiddlewareInterface
{
    private string $secretKey;
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
    }

    public function process(ServerRequestInterface $request, callable $next): ApiResponse
    {
        $authHeader = $request->getHeader('Authorization');
        if (is_array($authHeader)) {
            $authHeader = $authHeader[0];
        }
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['username' => $decoded->userName]);
            if ($user) {
                $_SESSION['user'] = $user;
                return $next($request);
            }

            return ApiResponse::fromPayload(['error' => 'Unauthorized'], 401);
        }

        return ApiResponse::fromPayload(['error' => 'Unauthorized'], 401);
    }
}
