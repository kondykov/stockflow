<?php

namespace StockFlow\Identity\Application\Command;

use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Identity\Domain\Security\CurrentUserInterface;
use StockFlow\Identity\Infrastructure\Extractor\UserExtractor;
use StockFlow\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
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
