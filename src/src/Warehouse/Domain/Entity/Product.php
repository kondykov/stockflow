<?php

namespace StockFlow\Warehouse\Domain\Entity;

use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;
use StockFlow\Warehouse\Domain\ValueObject\Sku;

class Product
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public readonly Sku $sku,
        public ?string $remoteId = null,
    ) {
    }
}
