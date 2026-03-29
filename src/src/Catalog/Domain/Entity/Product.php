<?php

namespace StockFlow\Catalog\Domain\Entity;

use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class Product
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public string $name,
        public Sku $sku,
    ) {
    }
}
