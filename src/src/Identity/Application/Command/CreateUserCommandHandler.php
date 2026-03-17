<?php

namespace StockFlow\Identity\Application\Command;

use Assert\Assert;
use StockFlow\Identity\Application\Extractor\UserExtractor;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Identity\Domain\Entity\Manager;
use StockFlow\Identity\Domain\Repository\UserRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserPasswordHasherInterface $hasher,
        private UserExtractor $extractor
    ) {
    }

    public function __invoke(CreateUserCommand $command): UserResponse
    {
        $userExists = $this->repository->findByEmail($command->email);

        Assert::that($userExists, 'Пользователь с таким email уже существует', 'email')->null();

        $user = new Manager();
        $user->email = $command->email;
        $user->password = $this->hasher->hashPassword($user, $command->password);

        $this->repository->save($user);

        return $this->extractor->extract($user);
    }
}
