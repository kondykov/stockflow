<?php

namespace StockFlow\Identity\Application\Query;

use StockFlow\Identity\Application\Extractor\UserExtractor;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Extractor\PaginationExtractor;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;

readonly class GetUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(GetUsersQuery $query): array
    {
        $data = $this->repository->findAllPaginated(
            page: $query->page,
            pageSize: $query->pageSize,
        );

        return new PaginationExtractor()->extract($data, $this->extractor);
    }
}
