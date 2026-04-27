<?php

namespace StockFlow\Identity\Application\Extractor;

use StockFlow\Identity\Application\Security\RoleNameNormalizer;
use StockFlow\Identity\Domain\Dto\RoleResponse;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<Role, RoleResponse>
 */
readonly class RoleExtractor implements ExtractorInterface
{
    public function __construct(
        private RoleNameNormalizer $normalizer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function extract(object $entity): RoleResponse
    {
        return new RoleResponse(
            id: $entity->id,
            name: $this->normalizer->normalize($entity->name),
            permissions: array_map(static fn(Permission $p) => [
                'name' => $p->value,
                'label' => $p->label(),
            ], $entity->permissions),
        );
    }
}
