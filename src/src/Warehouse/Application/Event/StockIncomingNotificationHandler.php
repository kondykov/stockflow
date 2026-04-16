<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Warehouse\Domain\Event\StockIncomingRecorded;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StockIncomingNotificationHandler
{
    public function __invoke(StockIncomingRecorded $event): void
    {
        echo sprintf(
            "--- NOTIFICATION ---\nТовар ID %d поступил на склад ID %d в количестве %d ед.\n--------------------\n",
            $event->productId,
            $event->warehouseId,
            $event->quantity
        );
    }
}
