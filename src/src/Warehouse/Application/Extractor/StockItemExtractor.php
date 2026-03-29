<?php

namespace StockFlow\Warehouse\Application\Extractor;

use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

/**
 * @implements ExtractorInterface<StockItem, StockItemResponse>
 */
class StockItemExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): StockItemResponse
    {
        return new StockItemResponse(
            id: $entity?->id,
            skuCode: $entity->sku->code,
            skuName: $entity->sku->name,
            remoteId: $entity?->remoteId,
            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
