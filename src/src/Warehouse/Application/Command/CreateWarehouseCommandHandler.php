<?php

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Identity\Application\Contract\Security\CurrentUserReferenceInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

readonly class CreateWarehouseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private WarehouseExtractor $extractor,
        private WarehouseRepositoryInterface $repository,
        private CurrentUserReferenceInterface $currentUserReference
    ) {
    }

    public function __invoke(CreateWarehouseCommand $command): WarehouseResponse
    {
        $exists = $this->repository->findByNameAndAddress($command->name, $command->address);

        Assert::that($exists, 'Склад с таким именем уже существует', 'name')
            ->null();

        $user = $this->currentUserReference->getUser();

        $wh = new Warehouse(
            userId: $user->id,
            name: $command->name,
            address: $command->address,
        );
        $this->repository->save($wh);

        return $this->extractor->extract($wh);
    }
}
