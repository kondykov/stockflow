<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Identity\Application\Contract\Security\CurrentUserReferenceInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

final readonly class UpdateWarehouseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private WarehouseExtractor $extractor,
        private WarehouseRepositoryInterface $repository,
        private CurrentUserReferenceInterface $currentUserReference
    ) {
    }

    public function __invoke(UpdateWarehouseCommand $command): WarehouseResponse
    {
        $warehouse = $this->repository->findById($command->id);

        Assert::lazy()
            ->that($warehouse, 'warehouse')->notNull('Склад не найден')
            ->that($warehouse->userId === $this->currentUserReference->getUser()->id, 'ownership')->true('У вас нет доступа к этому складу')
            ->verifyNow();

        if ($command->name !== null) {
            $exists = $this->repository->findByNameAndAddress($command->name, $warehouse->address);
            Assert::that($exists === null || $exists->id === $warehouse->id, 'unique')->true('Склад с таким именем уже существует');
            $warehouse->name = $command->name;
        }

        if ($command->address !== null) {
            $exists = $this->repository->findByNameAndAddress($warehouse->name, $command->address);
            Assert::that($exists === null || $exists->id === $warehouse->id, 'unique')->true('Склад с таким адресом уже существует');
            $warehouse->address = $command->address;
        }

        $this->repository->save($warehouse);

        return $this->extractor->extract($warehouse);
    }
}
