<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Shared\Kernel\Application\EventDispatcherInterface;
use StockFlow\Warehouse\Application\Extractor\StockExtractor;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;

readonly class IncomingStockItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockExtractor $extractor,
        private CurrentUserInterface $currentUser,
        private EventDispatcherInterface $eventDispatcher,
        private StockRepositoryInterface $stockRepository,
        private StockItemRepositoryInterface $stockItemRepository,
        private WarehouseRepositoryInterface $warehouseRepository,
    ) {
    }

    public function __invoke(IncomingStockItemCommand $command): StockResponse
    {
        $user = $this->currentUser->getUser();
        $warehouse = $this->warehouseRepository->findById($command->warehouseId);

        Assert::lazy()
            ->that($warehouse, 'warehouse')->notEmpty('Склад не найден')
            ->that($warehouse?->userId === $user->id, 'warehouse_access')->true('У вас нет доступа к этому складу')
            ->verifyNow();
        $stock = $this->stockRepository->findByWarehouseIdAndStockItemId($command->warehouseId, $command->stockItemId);

        if ($stock) {
            $stock->receive($command->quantity);
        } else {
            /** @var Warehouse $wh */
            $wh = $this->warehouseRepository->findById($command->warehouseId);
            /** @var StockItem $stockItem */
            $stockItem = $this->stockItemRepository->findById($command->stockItemId);

            Assert::lazy()
                ->that($stockItem, 'stockItemId')->notEmpty('Позиция товара не найдена')
                ->that($wh, 'warehouseId')->notEmpty('Склад не найден')
                ->verifyNow();

            $stock = new Stock(
                warehouse: $wh,
	            item: $stockItem,
                quantity: $command->quantity,
            );
        }

        $this->stockRepository->save($stock);

        foreach ($stock->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        return $this->extractor->extract($stock);
    }
}
