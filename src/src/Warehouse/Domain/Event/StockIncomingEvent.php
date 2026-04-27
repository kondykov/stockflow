<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Domain\Event;

use DateTimeImmutable;
use StockFlow\Shared\Kernel\Domain\DomainEvent;

/**
 * Событие: товар поступил на склад.
 * Возникает когда количество товара увеличивается через операцию receive().
 */
final class StockIncomingEvent extends DomainEvent
{
    public function __construct(
        public readonly int $warehouseId,
        public readonly int $stockItemId,
        public readonly int $quantity,
        int|string $aggregateId = 0,
        ?DateTimeImmutable $occurredAt = null,
        public ?string $correlationId = null
    ) {
        parent::__construct($aggregateId, $occurredAt);
    }
}
