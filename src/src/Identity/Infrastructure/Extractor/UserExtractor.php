<?php

namespace StockFlow\Identity\Infrastructure\Extractor;

use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Shared\Infrastructure\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<User>
 */
final readonly class UserExtractor implements ExtractorInterface
{
    /**
     * @param User|object $entity
     * @return array
     */
    public function extract(object $entity): array
    {
        return [
            'id' => $entity->id,

            'email' => $entity->email,
            'roles' => $entity->getRoles(),

            'createdAt' => $entity->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $entity->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
