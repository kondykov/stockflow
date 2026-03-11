<?php

namespace StockFlow\Identity\Domain\Entity\RBAC;

use StockFlow\Identity\Domain\ValueObject\RBAC\Permission;

class Role
{
    public ?int $id = null;
    public string $name;
    /**
     * @var list<Permission>
     */
    public array $permissions = [] {
        set => array_unique($value);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addPermission(Permission $permission): void
    {
        if (!in_array($permission, $this->permissions, true)) {
            $this->permissions = [...$this->permissions, $permission];
        }
    }

    public function hasPermission(Permission $type): bool
    {
        return in_array($type, $this->permissions, true);
    }

    public function removePermission(Permission $type): void
    {
        $this->permissions = array_filter(
            $this->permissions,
            fn(Permission $p) => $p !== $type
        );
    }
}
