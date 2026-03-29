<?php

namespace StockFlow\Shared\Kernel\Domain\Aggregate;

abstract class AggregateRoot
{
    private array $recordedEvents = [];

    public function pullDomainEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }

    protected function record(object $event): void
    {
        $this->recordedEvents[] = $event;
    }
}
