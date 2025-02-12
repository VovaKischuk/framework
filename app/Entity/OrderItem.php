<?php

declare(strict_types=1);

namespace Framework\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_items')]
class OrderItem
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'float')]
    private float $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
