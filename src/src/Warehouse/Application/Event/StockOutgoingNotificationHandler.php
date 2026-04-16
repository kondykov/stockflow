<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Warehouse\Domain\Event\StockOutgoingRecorded;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StockOutgoingNotificationHandler
{
    public function __invoke(StockOutgoingRecorded $event): void
    {
        echo sprintf(
            "--- NOTIFICATION ---\nТовар ID %d был отгружен со склада ID %d в количестве %d ед.\n--------------------\n",
            $event->productId,
            $event->warehouseId,
            $event->quantity
        );
    }
}
