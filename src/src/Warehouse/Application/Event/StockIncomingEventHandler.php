<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Warehouse\Domain\Event\StockIncomingEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

//#[AsMessageHandler]
class StockIncomingEventHandler
{
    public function __invoke(StockIncomingEvent $event): void
    {
        echo sprintf(
            "--- NOTIFICATION ---\nТовар ID %d поступил на склад ID %d в количестве %d ед.\n--------------------\n",
            $event->stockItemId,
            $event->warehouseId,
            $event->quantity
        );
    }
}
