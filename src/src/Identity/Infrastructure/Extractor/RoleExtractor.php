<?php

namespace StockFlow\Identity\Infrastructure\Extractor;

use StockFlow\Identity\Domain\Dto\RoleResponse;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Identity\Domain\ValueObject\RBAC\Permission;
use StockFlow\Shared\Infrastructure\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<Role, RoleResponse>
 */
class RoleExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): RoleResponse
    {
        return new RoleResponse(
            id: $entity->id,
            name: $entity->name,
            permissions: array_map(static fn(Permission $p) => [
                'name' => $p->value,
                'label' => $p->label(),
            ], $entity->permissions),
        );
    }
}
