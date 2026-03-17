<?php

namespace StockFlow\Warehouse\Domain\ValueObject;

final readonly class StockResponse
{
    public function __construct(
        public int $warehouseId,
        public int $productId,
        public int $onHand,
    ) {
    }
}
