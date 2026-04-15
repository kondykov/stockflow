<?php

namespace StockFlow\Catalog\Domain\Entity;

use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class ProductAttribute
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public Product $product,
        public string $key,
        public string $value,
    ) {
    }
}
