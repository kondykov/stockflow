<?php

declare(strict_types=1);

namespace StockFlow\Shared\Kernel\Infrastructure\Messenger;

use StockFlow\Shared\Kernel\Application\EventDispatcherInterface;
use StockFlow\Shared\Kernel\Domain\DomainEventInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Реализация EventDispatcher используя Symfony Messenger.
 * Диспетчеризирует domain events в message bus для асинхронной (или синхронной) обработки.
 */
final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(DomainEventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
