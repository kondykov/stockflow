<?php

namespace StockFlow\Shared\Catalog\Domain\ValueObject;

readonly class ProductReference
{
    public function __construct(
        public readonly int $id,
        public readonly Sku $sku,
    ) {
    }
}
