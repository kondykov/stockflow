<?php

namespace StockFlow\Warehouse\Application\Extractor;

use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;
use StockFlow\Warehouse\Domain\Entity\Product;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\ValueObject\ProductResponse;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

/**
 * @implements ExtractorInterface<Product, ProductResponse>
 */
class ProductExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): ProductResponse
    {
        return new ProductResponse(
            id: $entity?->id,
            skuCode: $entity->sku->code,
            skuName: $entity->sku->name,
            remoteId: $entity?->remoteId,
            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
