<?php

namespace StockFlow\Warehouse\Application\Extractor;

use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
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
		return new StockResponse(
            warehouseId: $entity->warehouse->id,
            productId: $entity->product->id,
            onHand: $entity->onHands,
        );
	}
}
