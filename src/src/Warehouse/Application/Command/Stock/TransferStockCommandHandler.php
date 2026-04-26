<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Shared\Kernel\Application\EventDispatcherInterface;
use StockFlow\Warehouse\Domain\Aggregate\Stock;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use Symfony\Component\Uid\Uuid;

readonly class TransferStockCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockRepositoryInterface $stockRepository,
        private WarehouseRepositoryInterface $warehouseRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(TransferStockCommand $command): void
    {
        $correlationId = Uuid::v7()->toRfc4122();

        $sourceWh = $this->warehouseRepository->findById($command->fromWarehouseId);
        $targetWh = $this->warehouseRepository->findById($command->toWarehouseId);

        Assert::lazy()
            ->that($sourceWh, 'source')->notEmpty('Склад отправитель не найден')
            ->that($targetWh, 'target')->notEmpty('Склад получатель не найден')
            ->verifyNow();

        $sourceStock = $this->stockRepository->findByWarehouseIdAndStockItemId(
            $command->fromWarehouseId,
            $command->stockId
        );

        Assert::that($sourceStock)->notEmpty('Товар на складе отправителе не найден', 'stockItemId');

        $sourceStock->deduct($command->quantity, $correlationId);

        $targetStock = $this->stockRepository->findByWarehouseIdAndStockItemId(
            $command->toWarehouseId,
            $command->stockId
        );

        if (!$targetStock) {
            $targetStock = new Stock($targetWh, $sourceStock->item, 0);
        }

        $targetStock->receive($command->quantity, $correlationId);

        $this->stockRepository->save($sourceStock);
        $this->stockRepository->save($targetStock);

        foreach (array_merge($sourceStock->pullDomainEvents(), $targetStock->pullDomainEvents()) as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
