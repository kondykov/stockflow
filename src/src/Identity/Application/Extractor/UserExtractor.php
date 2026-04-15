<?php

namespace StockFlow\Identity\Application\Extractor;

use StockFlow\Identity\Application\Security\RoleNameNormalizer;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Identity\Infrastructure\Persistence\Doctrine\Repository\RoleRepository;
use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<User, UserResponse>
 */
final readonly class UserExtractor implements ExtractorInterface
{
    public function __construct(
        private RoleNameNormalizer $normalizer,
        private RoleRepository $roleRepository,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function extract(object $entity): UserResponse
    {
        return new UserResponse(
            id: $entity->id,

            email: $entity->email,
            username: $entity->name,
            rolesIds: $this->roleRepository->findIdsByNames($this->normalizer->normalizeArray($entity->getRoles())),

            isAdmin: $entity->isAdmin(),

            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
