<?php

namespace StockFlow\Identity\Application\Query;

use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Infrastructure\Extractor\UserExtractor;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;

readonly class GetCurrentUserDataQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CurrentUserInterface $currentUser,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(GetCurrentUserDataQuery $query): UserResponse
    {
        $user = $this->currentUser->getUser();

        return $this->extractor->extract($user);
    }
}
