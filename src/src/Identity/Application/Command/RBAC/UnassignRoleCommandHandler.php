<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use Assert\Assert;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

final readonly class UnassignRoleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
        private CurrentUserInterface $currentUser,
    ) {
    }

    public function __invoke(UnassignRoleCommand $command): void
    {
        $user = $this->currentUser->getUser();

        Assert::that($user, 'Доступно только администраторам', 'user')
            ->isInstanceOf(Admin::class);

        $role = $this->roleRepository->findByName($command->role);
        $userForAssignRole = $this->userRepository->findById($command->userId);

        Assert::lazy()
            ->that($role, 'role', 'Роль не существует')
            ->notNull()
            ->that($userForAssignRole, 'user', 'Пользователь не существует')
            ->notNull()
            ->that($userForAssignRole, 'user', 'У администратора нет ролей')
            ->notIsInstanceOf(Admin::class)
            ->verifyNow();

        $userForAssignRole->removeRole($role);
        $this->userRepository->save($userForAssignRole);
    }
}
