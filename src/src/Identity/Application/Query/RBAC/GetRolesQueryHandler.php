<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Identity\Application\Extractor\RoleExtractor;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Extractor\PaginationExtractor;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
readonly class GetRolesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private RoleExtractor $extractor,
        private RoleRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetRolesQuery $query): array
    {
        $data = $this->repository->findAllPaginated(
            page: $query->page,
            pageSize: $query->pageSize,
        );

        return new PaginationExtractor()->extract($data, $this->extractor);
    }
}
