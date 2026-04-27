<?php

namespace StockFlow\Catalog\Domain\Event;

use DateTimeImmutable;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Domain\DomainEvent;

class SyncProductEvent extends DomainEvent
{
    public function __construct(
        public Sku $sku,
        int|string $aggregateId,
        ?DateTimeImmutable $occurredAt = null
    )
    {
        parent::__construct($aggregateId, $occurredAt);
    }
}
