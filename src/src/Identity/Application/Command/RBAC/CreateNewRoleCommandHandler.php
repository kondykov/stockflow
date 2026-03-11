<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use Assert\Assert;
use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Identity\Domain\Security\CurrentUserInterface;
use StockFlow\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
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

        $roleExists = $this->roleRepository->findByName($command->role);

        Assert::lazy()
            ->that($roleExists, 'role')
            ->null('Роль уже существует')
            ->that(strtoupper($command->role), 'role')
            ->notContains('ADMIN', 'Название роли не может содержать "ADMIN" (это системный зарезервированный тип)')
            ->notContains('ROLE', 'В названии роли не нужно передавать "ROLE", префикс добавиться автоматически')
            ->verifyNow();

        $role = new Role($command->role);
        $role->permissions = $command->permissions;

        $this->roleRepository->save($role);

        return $role;
    }
}
