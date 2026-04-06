<?php

namespace StockFlow\Identity\Application\Query;

use Assert\Assert;
use StockFlow\Identity\Application\Extractor\UserExtractor;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;

readonly class GetUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(GetUserByIdQuery $query): UserResponse
    {
        $user = $this->repository->findById($query->id);

        Assert::that($user)->notNull('Пользователь не найден', 'id');

        return $this->extractor->extract($user);
    }
}
