<?php

declare(strict_types=1);

namespace StockFlow\Shared\Kernel\Application;

use StockFlow\Shared\Kernel\Domain\DomainEventInterface;

/**
 * Интерфейс для диспетчера domain events.
 * Отвечает за отправку событий подписчикам (обработчикам).
 */
interface EventDispatcherInterface
{
    /**
     * Диспетчеризация события всем зарегистрированным обработчикам.
     *
     * @param DomainEventInterface $event Событие для отправки
     */
    public function dispatch(DomainEventInterface $event): void;
}
