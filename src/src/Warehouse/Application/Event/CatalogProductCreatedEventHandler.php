<?php

namespace StockFlow\Warehouse\Application\Event;

use StockFlow\Shared\Catalog\Domain\Events\ProductCreatedEvent;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CatalogProductCreatedEventHandler
{
    public function __construct(
        private StockItemRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $stockItem = new StockItem(
            sku: new Sku($event->skuCode, $event->skuName),
            remoteId: $event->aggregateId()
        );

        $this->repository->save($stockItem);

        echo sprintf(
            "--- NOTIFICATION ---\nЗарегистрирован новый товар[%d]: %s\n--------------------\n",
            $event->aggregateId(),
            $event->name,
        );
    }
}
