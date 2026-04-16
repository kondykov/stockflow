<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Domain\Event;

use DateTimeImmutable;
use StockFlow\Shared\Kernel\Domain\DomainEvent;

/**
 * Событие: товар отгружен со склада.
 * Возникает когда количество товара уменьшается через операцию deduct().
 */
final class StockOutgoingRecorded extends DomainEvent
{
    public function __construct(
        public readonly int $warehouseId,
        public readonly int $stockItemId,
        public readonly int $quantity,
        int|string $aggregateId = 0,
        ?DateTimeImmutable $occurredAt = null,
    ) {
        parent::__construct($aggregateId, $occurredAt);
    }
}
