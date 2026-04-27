<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Warehouse\Domain\Event\StockOutgoingEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

//#[AsMessageHandler]
class StockOutgoingEventHandler
{
    public function __invoke(StockOutgoingEvent $event): void
    {
        echo sprintf(
            "--- NOTIFICATION ---\nТовар ID %d был отгружен со склада ID %d в количестве %d ед.\n--------------------\n",
            $event->stockItemId,
            $event->warehouseId,
            $event->quantity
        );
    }
}
