<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Identity\Application\Extractor\RoleExtractor;
use StockFlow\Identity\Application\Security\RoleNameNormalizer;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class GetUserRolesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private Security $security,
        private RoleExtractor $extractor,
        private RoleNameNormalizer $normalizer,
        private RoleRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetUserRolesQuery $query): array
    {
        $user = $this->security->getUser();

        $roles = $this->repository->findByNames($this->normalizer->normalizeArray($user->getRoles()));

        $extractedRoles  = [];

        foreach ($roles as $roleId) {
            $extractedRoles[] = $this->extractor->extract($roleId);
        }

        return $extractedRoles;
    }
}
