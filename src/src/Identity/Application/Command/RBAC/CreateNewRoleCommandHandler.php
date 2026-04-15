<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use Assert\Assert;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

readonly class CreateNewRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
        private CurrentUserInterface $currentUser,
    ) {
    }

    public function __invoke(CreateNewRoleCommand $command): Role
    {
        $user = $this->currentUser->getUser();

        Assert::that($user, 'Доступно только администраторам', 'user')
            ->isInstanceOf(Admin::class);

        $roleExists = $this->roleRepository->findByName($command->name);

        Assert::lazy()
            ->that($roleExists, 'role')
            ->null('Роль уже существует')
            ->that(strtoupper($command->name), 'role')
            ->notContains('ADMIN', 'Название роли не может содержать "ADMIN" (это системный зарезервированный тип)')
            ->notContains('ROLE', 'В названии роли не нужно передавать "ROLE", префикс добавиться автоматически')
            ->verifyNow();

        $role = new Role(strtoupper($command->name));
        $enums = array_map(
            static fn(string $value) => Permission::from($value),
            $command->permissions
        );

        $role->permissions = $enums;

        $this->roleRepository->save($role);

        return $role;
    }
}
