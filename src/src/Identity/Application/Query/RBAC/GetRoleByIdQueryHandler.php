<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Identity\Application\Extractor\RoleExtractor;
use StockFlow\Identity\Domain\Dto\RoleResponse;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;

readonly class GetRoleByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private RoleExtractor $extractor,
        private RoleRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetRoleByIdQuery $query): RoleResponse
    {
        $data = $this->repository->findById($query->id);

        return $this->extractor->extract($data);
    }
}
