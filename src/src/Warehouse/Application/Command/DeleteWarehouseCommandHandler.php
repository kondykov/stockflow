<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Identity\Application\Contract\Security\CurrentUserReferenceInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;

final readonly class DeleteWarehouseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private WarehouseRepositoryInterface $repository,
        private CurrentUserReferenceInterface $currentUserReference
    ) {
    }

    public function __invoke(DeleteWarehouseCommand $command): void
    {
        throw new ForbiddenOverwriteException('Удаление склада запрещено. Пожалуйста, обратитесь к администратору для получения доступа.');

        $warehouse = $this->repository->findById($command->id);

        Assert::lazy()
            ->that($warehouse, 'warehouse')->notNull('Склад не найден')
            ->that($warehouse->userId === $this->currentUserReference->getUser()->id, 'ownership')->true('У вас нет доступа к этому складу')
            ->verifyNow();

        $this->repository->delete($warehouse);
    }
}

