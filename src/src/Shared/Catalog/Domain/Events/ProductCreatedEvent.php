<?php

namespace StockFlow\Shared\Catalog\Domain\Events;

use DateTimeImmutable;
use StockFlow\Shared\Kernel\Domain\DomainEvent;

class ProductCreatedEvent extends DomainEvent
{
    public function __construct(
        public readonly string $name,
        public readonly string $skuCode,
        public readonly string $skuName,
        int|string $aggregateId = 0,
        ?DateTimeImmutable $occurredAt = null,
    ) {
        parent::__construct($aggregateId, $occurredAt);
    }
}
