<?php

namespace StockFlow\Document\Domain\Entity;

use StockFlow\Document\Domain\Enum\DocumentType;

class InventoryMovementDocument extends BaseDocument
{
    public function __construct(
        DocumentType $type,
        public int $warehouseId,
        public int $stockItemId,
        public int $quantity,
        public ?string $reason = null,
        public ?string $correlationId = null
    ) {
        parent::__construct($type);
    }
}
