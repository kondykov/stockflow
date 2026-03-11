<?php

namespace StockFlow\Shared\Infrastructure\Extractor;

/**
 * @template T of object
 * @template R
 */
interface ExtractorInterface
{
    /**
     * @param T $entity
     * @return R
     */
    public function extract(object $entity): mixed;
}
