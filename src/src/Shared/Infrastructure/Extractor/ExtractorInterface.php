<?php

namespace StockFlow\Shared\Infrastructure\Extractor;

/**
 * @template T of object
 */
interface ExtractorInterface
{
    /**
     * @param T $entity
     * @return array<string, mixed>
     */
    public function extract(object $entity): array;
}
