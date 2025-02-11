<?php

declare(strict_types=1);

namespace Framework\Transformer;

class ProductTransformer extends AbstractTransformer
{
    public function transform(mixed $data): array
    {
        return [
            'name' => $data->getName(),
            'quantity' => $data->getQuantity(),
            'price' => $data->getPrice(),
        ];
    }
}
