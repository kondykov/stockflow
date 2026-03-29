<?php

namespace StockFlow\Document\Domain\Entity;

use StockFlow\Document\Domain\Enum\DocumentType;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

abstract class BaseDocument
{
    use TimeStamps;

    public private(set) ?int $id = null;
    /**
     * Документ-основание
     */
    public ?BaseDocument $parent = null;

    public function __construct(
        public DocumentType $type,
    ) {
    }
}
