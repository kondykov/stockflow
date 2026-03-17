<?php

namespace StockFlow\Warehouse\Domain\ValueObject;

readonly class Sku
{
    public function __construct(
        public string $code,
        public string $name,
    ) {
    }
}
