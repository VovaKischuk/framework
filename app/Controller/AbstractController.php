<?php

declare(strict_types=1);

namespace Framework\Controller;

use Framework\Response\ApiResponseSerializer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Request;
use JsonException;

abstract class AbstractController
{
    /**
     * @throws JsonException
     */
    public function getRequestPayload(Request $request): array
    {
        return \json_decode(json: $request->getContent(), associative: true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<object> $data
     * @param array<string> $includes
     *
     * @return array<array-key,array<array-key, mixed>>
     */
    public function collection(array $data, ?TransformerAbstract $transformer = null, array $includes = []): array
    {
        return $this->createResourceData(new Collection($data, $transformer), $includes);
    }

    /**
     * @param array<string> $includes
     *
     * @return array<array-key, mixed>
     */
    public function item(mixed $data, ?TransformerAbstract $transformer = null, array $includes = []): array
    {
        return $this->createResourceData(new Item($data, $transformer), $includes);
    }

    /**
     * @param array<string> $includes
     *
     * @return array<array-key, mixed>
     */
    private function createResourceData(ResourceAbstract $resource, array $includes = []): array
    {
        $fractal = new Manager();
        $fractal->setSerializer(new ApiResponseSerializer());

        if ($includes) {
            $fractal->parseIncludes($includes);
        }

        return $fractal->createData($resource)->toArray();
    }
}
