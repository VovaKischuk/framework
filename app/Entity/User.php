<?php

declare(strict_types=1);

namespace Framework\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $role; // 'admin' or 'user'

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
