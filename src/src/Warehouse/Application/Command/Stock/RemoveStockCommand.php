<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;

class RemoveStockCommand implements CommandInterface
{
    public function __construct(
        public int $warehouseId,
        public int $stockId,
    ) {
    }
}
