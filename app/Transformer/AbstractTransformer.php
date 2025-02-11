<?php

declare(strict_types=1);

namespace Framework\Transformer;

use DateTime;
use DateTimeInterface;
use League\Fractal\TransformerAbstract;

abstract class AbstractTransformer extends TransformerAbstract
{
    abstract public function transform(mixed $data): array;

    /**
     * Formats DateTime data into ISO 8601 string.
     *
     * @param ?DateTimeInterface $rawDatetime
     */
    protected function formatDateTime(?DateTimeInterface $rawDatetime): ?string
    {
        if (!$rawDatetime) {
            return null;
        }

        return DateTime::createFromInterface($rawDatetime)?->format(DateTimeInterface::ATOM);
    }
}
