<?php

declare(strict_types=1);

namespace Framework\Response;

use League\Fractal\Serializer\ArraySerializer;

class ApiResponseSerializer extends ArraySerializer
{
    /**
     * {@inheritDoc}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }
}
