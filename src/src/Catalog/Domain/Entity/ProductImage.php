<?php

namespace StockFlow\Catalog\Domain\Entity;

use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class ProductImage
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public function __construct(
        public Product $product,
        public string $path,
        public bool $isCover,
    ) {
    }
}
