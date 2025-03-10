<?php

declare(strict_types=1);

namespace Framework\Entity;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;

    #[ORM\Column(type: 'float')]
    private float $totalPrice;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}
