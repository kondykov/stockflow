<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use Assert\Assert;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

final readonly class UpdateRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
    ) {
    }

    public function __invoke(UpdateRoleCommand $command): void
    {
        $role = $this->roleRepository->findById($command->id);
        Assert::that($role)->notNull('Роль не найдена', 'role');

        $role->name = $command->name;
        $permissions = [];

        foreach ($command->permissions as $permissionValue) {
            if ($permission = Permission::tryFrom($permissionValue)) {
                $permissions[] = $permission;
            }
        }

        $role->permissions = $permissions;
    }
}
