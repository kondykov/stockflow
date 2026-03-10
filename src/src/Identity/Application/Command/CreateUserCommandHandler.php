<?php

namespace StockFlow\Identity\Application\Command;

use Assert\Assert;
use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Identity\Infrastructure\Extractor\UserExtractor;
use StockFlow\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserPasswordHasherInterface $hasher,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(CreateUserCommand $command): array
    {
        $userExists = $this->repository->findByEmail($command->email);

        Assert::that($userExists, 'Пользователь с таким email уже существует', 'email')->null();

        $user = new User();
        $user->email = $command->email;
        $user->password = $this->hasher->hashPassword($user, $command->password);

        $this->repository->save($user);

        return $this->extractor->extract($user);
    }
}
