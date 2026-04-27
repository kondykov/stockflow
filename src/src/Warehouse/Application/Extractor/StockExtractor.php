<?php

namespace StockFlow\Warehouse\Application\Extractor;

use StockFlow\Shared\Kernel\Application\Extractor\ExtractorInterface;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;


/**
 * @implements ExtractorInterface<Stock, StockResponse>
 */
class StockExtractor implements ExtractorInterface
{

	/**
	 * @inheritDoc
	 */
	public function extract(object $entity): StockResponse
	{
        /** @var StockItem $item */
        $item = $entity->item;

		return new StockResponse(
            warehouseId: $entity->warehouse->id,
            stockItemId: $entity->item->id,
            onHand: $entity->onHands,

            skuCode: $item->sku->code,
            skuName: $item->sku->name,
            productId: $item->remoteId,
        );
	}
}
