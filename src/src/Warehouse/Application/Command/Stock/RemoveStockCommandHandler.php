<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use Assert\Assert;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Domain\Repository\StockRepositoryInterface;

readonly class RemoveStockCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CurrentUserInterface $currentUser,
        private StockRepositoryInterface $stockRepository,
    ) {
    }

    public function __invoke(RemoveStockCommand $command): true
    {
        $user = $this->currentUser->getUser();
        $stock = $this->stockRepository->findByWarehouseIdAndStockItemId($command->warehouseId, $command->stockId);

        Assert::lazy()
            ->that($stock?->warehouse, 'warehouseId')
            ->notEmpty('Склад не найден')
            ->that($stock->warehouse?->userId === $user->id, 'warehouse_access')
            ->true('У вас нет доступа к этому складу')
            ->that($stock, 'stockId')
            ->notEmpty('Сток не найден')
            ->that($stock->onHands, 'stockId')
            ->lessOrEqualThan(0, 'Невозможно удалить сток с остатком на складе')
            ->verifyNow();

        $this->stockRepository->remove($stock);

        return true;
    }
}
