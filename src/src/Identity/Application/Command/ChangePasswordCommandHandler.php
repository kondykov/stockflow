<?php

namespace StockFlow\Identity\Application\Command;

use StockFlow\Identity\Application\Extractor\UserExtractor;
use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class ChangePasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CurrentUserInterface $currentUser,
        private UserRepositoryInterface $repository,
        private UserPasswordHasherInterface $hasher,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(ChangePasswordCommand $command): UserResponse
    {
        $user = $this->currentUser->getUser();

        $user->password = $this->hasher->hashPassword($user, $command->newPassword);
        $this->repository->save($user);

        return $this->extractor->extract($user);
    }
}
