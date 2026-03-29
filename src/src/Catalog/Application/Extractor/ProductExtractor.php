<?php

namespace StockFlow\Catalog\Application\Extractor;

use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<Product, ProductResponse>
 */
readonly class ProductExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): ProductResponse
    {
        return new ProductResponse(
            id: $entity->id,
            name: $entity->name,
            sku: $entity->sku->code,
            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
