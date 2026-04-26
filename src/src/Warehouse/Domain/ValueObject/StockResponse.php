<?php

namespace StockFlow\Warehouse\Domain\ValueObject;

final readonly class StockResponse
{
    public function __construct(
        public int $warehouseId,
        public int $stockItemId,
        public int $onHand,
        public string $skuCode,
        public string $skuName,
        public int $productId,
    ) {
    }
}
