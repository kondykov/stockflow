<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Identity\Application\Query\GetCurrentUserDataQuery;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Infrastructure\Extractor\UserExtractor;
use StockFlow\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetRolesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private Security $security,
        private UserExtractor $extractor
    )
    {
    }

    public function __invoke(GetCurrentUserDataQuery $query): UserResponse
    {
        $user = $this->security->getUser();

        return $this->extractor->extract($user);
    }
}
