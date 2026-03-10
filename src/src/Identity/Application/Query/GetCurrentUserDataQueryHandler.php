<?php

namespace StockFlow\Identity\Application\Query;

use StockFlow\Identity\Infrastructure\Extractor\UserExtractor;
use StockFlow\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetCurrentUserDataQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private Security $security,
        private UserExtractor $extractor
    )
    {
    }

    public function __invoke(GetCurrentUserDataQuery $query): array
    {
        $user = $this->security->getUser();

        return $this->extractor->extract($user);
    }
}
