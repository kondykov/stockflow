<?php

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Identity\Application\Contract\Security\CurrentUserReferenceInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

readonly class UpdateWarehouseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private WarehouseExtractor $extractor,
        private WarehouseRepositoryInterface $repository
    ) {
    }

    public function __invoke(UpdateWarehouseCommand $command): WarehouseResponse
    {
        /** @var Warehouse $existsById */
        $existsById = $this->repository->findById($command->id);
        $exists = $this->repository->findByNameAndAddress($command->name, $command->address);

        Assert::lazy()
            ->that($existsById, 'id')->notEmpty('Склад не найден')
            ->that($exists, 'name')->nullOr()->false('Склад с таким названием и адресом уже существует')
            ->verifyNow();

        $existsById->name = $command->name;
        $existsById->address = $command->address;

        $this->repository->save($existsById);

        return $this->extractor->extract($existsById);
    }
}
