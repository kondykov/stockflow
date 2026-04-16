<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Warehouse\Domain\Event\StockMovementRecorded;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StockMovementNotificationHandler
{
    public function __invoke(StockMovementRecorded $event): void
    {
        $diff = $event->quantityDiff;
        $direction = $diff > 0 ? 'увеличился' : 'уменьшился';

        echo sprintf(
            "КОРРЕКТИРОВКА ОСТАТКОВ\n" .
            "Склад: %d, Товар: %d\n" .
            "Зафиксировано движение: %d ед. (Запас %s)\n" .
            "Старый остаток: %d -> Новый остаток: %d\n" .
            "Причина: %s\n",
            $event->warehouseId,
            $event->productId,
            abs($diff),
            $direction,
            $event->oldQuantity,
            $event->newQuantity,
            $event->reason ?? 'не указана'
        );
    }
}
