<?php

namespace StockFlow\Shared\EventListener;

use StockFlow\Catalog\Domain\Event\SyncProductEvent;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class SyncProductsEventListener
{
    public function __construct(
        private StockItemRepositoryInterface $repository
    ) {
    }

    #[AsMessageHandler]
    public function onSyncProducts(SyncProductEvent $event): void
    {
        $stockItem = $this->repository->findBySkuCode($event->sku->code);

        if ($stockItem) {
            return;
        }

        $stockItem = new StockItem(
            sku: new Sku($event->sku->code, $event->sku->name),
            remoteId: $event->aggregateId()
        );

        $this->repository->save($stockItem);
    }
}
