<?php

namespace StockFlow\Document\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;

class GetMovementHistoryQuery implements QueryInterface
{
    public function __construct(
        public int $warehouseId,
        public int $page = 1,
        public int $pageSize = 20,
        public ?string $search = null
    ) {}
}
