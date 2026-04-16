<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Domain\Event;

use DateTimeImmutable;
use StockFlow\Shared\Kernel\Domain\DomainEvent;

/**
 * Событие: количество товара откорректировано.
 * Возникает когда количество товара изменяется через операцию adjust().
 */
final class StockMovementRecorded extends DomainEvent
{
    public function __construct(
        public readonly int $warehouseId,
        public readonly int $stockItemId,
        public readonly int $oldQuantity,
        public readonly int $newQuantity,
        public readonly ?string $reason = null,
        int|string $aggregateId = 0,
        ?DateTimeImmutable $occurredAt = null,
    ) {
        parent::__construct($aggregateId, $occurredAt);
    }

    public int $quantityDiff {
        get => $this->newQuantity - $this->oldQuantity;
    }
}
