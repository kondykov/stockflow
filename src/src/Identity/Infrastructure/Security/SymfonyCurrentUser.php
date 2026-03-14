<?php

namespace StockFlow\Identity\Infrastructure\Security;

use StockFlow\Identity\Application\Security\CurrentUserInterface;
use StockFlow\Identity\Domain\Entity\User;
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
