<?php

namespace StockFlow\Identity\Infrastructure\Security;

use StockFlow\Identity\Domain\Entity\User;
use StockFlow\Identity\Domain\Security\CurrentUserInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SymfonyCurrentUser implements CurrentUserInterface
{

    public function __construct(
        private Security $security,
    ) {
    }

    public function getUser(): ?User
    {
        $user = $this->security->getUser();

        return $user instanceof User ? $user : null;
    }
}
