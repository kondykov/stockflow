<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Identity\Application\Extractor\UserExtractor;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
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

    public function __invoke(GetRolesQuery $query): UserResponse
    {
        $user = $this->security->getUser();

        return $this->extractor->extract($user);
    }
}
