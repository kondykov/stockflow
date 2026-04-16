<?php

declare(strict_types=1);

namespace StockFlow\Shared\Kernel\Domain;

use DateTimeImmutable;

/**
 * Базовый абстрактный класс для всех domain events.
 * Автоматически обрабатывает timestamp и ID агрегата.
 */
abstract class DomainEvent implements DomainEventInterface
{
    private DateTimeImmutable $occurredAt;

    public function __construct(
        private readonly int|string $aggregateId,
        ?DateTimeImmutable $occurredAt = null,
    ) {
        $this->occurredAt = $occurredAt ?? new DateTimeImmutable();
    }

    final public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    final public function aggregateId(): int|string
    {
        return $this->aggregateId;
    }
}
