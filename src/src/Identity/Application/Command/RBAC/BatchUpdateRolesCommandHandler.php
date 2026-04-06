<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Identity\Domain\Repository\RoleRepositoryInterface;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

final readonly class BatchUpdateRolesCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
        private CurrentUserInterface $currentUser,
    ) {
    }

    public function __invoke(BatchUpdateRolesCommand $command): void
    {
        $user = $this->currentUser->getUser();

        Assert::that($user, 'Доступно только администраторам', 'user')
            ->isInstanceOf(Admin::class);

        $userForAssignRole = $this->userRepository->findById($command->userId);

        Assert::lazy()
            ->that($userForAssignRole, 'user', 'Пользователь не существует')
            ->notNull()
            ->that($userForAssignRole, 'user', 'Невозможно добавить роль администратору')
            ->notIsInstanceOf(Admin::class)
            ->verifyNow();

        $roles = [];
        foreach ($command->roles as $roleName) {
            $role = $this->roleRepository->findByName(strtoupper($roleName));

            Assert::that($role, 'role', sprintf('Роль "%s" не существует', strtoupper($roleName)))
                ->notNull();

            $roles[] = $role;
        }

        $userForAssignRole->setUserRoles(new ArrayCollection($roles));

        $this->userRepository->save($userForAssignRole);
    }
}
