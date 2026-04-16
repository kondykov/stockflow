<?php

declare(strict_types=1);

namespace StockFlow\Shared\Kernel\Domain;

use DateTimeImmutable;

/**
 * Interface для всех domain-level событий.
 * Реализуется всеми событиями, которые записываются в агрегаты для обозначения значимых изменений бизнес-логики.
 */
interface DomainEventInterface
{
    /**
     * Время наступления события.
     */
    public function occurredAt(): DateTimeImmutable;

    /**
     * ID агрегата, в котором произошло событие.
     *
     * @return int|string
     */
    public function aggregateId(): int|string;
}
