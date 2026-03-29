<?php

namespace StockFlow\Identity\Application\Extractor;

use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Shared\Kernel\Infrastructure\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<User, UserResponse>
 */
final readonly class UserExtractor implements ExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function extract(object $entity): UserResponse
    {
        return new UserResponse(
            id: $entity->id,

            email: $entity->email,
            roles: $entity->getRoles(),

            isAdmin: $entity->isAdmin(),

            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
