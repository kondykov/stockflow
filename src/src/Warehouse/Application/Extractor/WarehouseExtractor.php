<?php

namespace StockFlow\Warehouse\Application\Extractor;

use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

/**
 * @implements ExtractorInterface<Warehouse, WarehouseResponse>
 */
class WarehouseExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): WarehouseResponse
    {
        return new WarehouseResponse(
            id: $entity?->id,
            name: $entity->name,
            address: $entity->address,
            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
