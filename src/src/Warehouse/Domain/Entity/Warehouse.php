<?php

namespace StockFlow\Warehouse\Domain\Entity;

use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class Warehouse
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public int $userId,
        public string $name,
        public string $address,
    ) {
    }
}
