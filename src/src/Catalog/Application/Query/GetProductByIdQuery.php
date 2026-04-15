<?php

namespace StockFlow\Catalog\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;

final readonly class GetProductByIdQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    ) {
    }
}
