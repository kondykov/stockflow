<?php

namespace StockFlow\Warehouse\Domain\Entity;

use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class StockItem
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public readonly Sku $sku,
        public ?string $remoteId = null,
    ) {}

    public int $quantity;
}
