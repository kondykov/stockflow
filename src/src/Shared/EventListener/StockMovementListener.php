<?php

namespace StockFlow\Shared\EventListener;

use StockFlow\Document\Domain\Entity\InventoryMovementDocument;
use StockFlow\Document\Domain\Enum\DocumentType;
use StockFlow\Document\Domain\Repository\DocumentRepositoryInterface;
use StockFlow\Warehouse\Domain\Event\StockIncomingEvent;
use StockFlow\Warehouse\Domain\Event\StockMovementEvent;
use StockFlow\Warehouse\Domain\Event\StockOutgoingEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class StockMovementListener
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository
    ) {
    }

    #[AsMessageHandler]
    public function onIncoming(StockIncomingEvent $event): void
    {
        $this->documentRepository->save(new InventoryMovementDocument(
            type: DocumentType::Inbound,
            warehouseId: $event->warehouseId,
            stockItemId: $event->stockItemId,
            quantity: $event->quantity,
            correlationId: $event->correlationId
        ));
    }

    #[AsMessageHandler]
    public function onOutgoing(StockOutgoingEvent $event): void
    {
        $this->documentRepository->save(new InventoryMovementDocument(
            type: DocumentType::Outbound,
            warehouseId: $event->warehouseId,
            stockItemId: $event->stockItemId,
            quantity: -$event->quantity,
            correlationId: $event->correlationId
        ));
    }

    #[AsMessageHandler]
    public function onAdjustment(StockMovementEvent $event): void
    {
        $diff = $event->newQuantity - $event->oldQuantity;
        $this->documentRepository->save(new InventoryMovementDocument(
            type: DocumentType::Adjustment,
            warehouseId: $event->warehouseId,
            stockItemId: $event->stockItemId,
            quantity: $diff,
            correlationId: $event->correlationId
        ));
    }
}
