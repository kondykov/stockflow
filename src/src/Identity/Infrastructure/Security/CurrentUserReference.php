<?php

namespace StockFlow\Identity\Infrastructure\Security;

use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Shared\Identity\Application\Contract\Security\CurrentUserReferenceInterface;
use StockFlow\Shared\Identity\Domain\ValueObject\UserReference;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class CurrentUserReference implements CurrentUserReferenceInterface
{
    public function __construct(
        private SymfonyCurrentUser $currentUser
    ) {
    }

    public function isAdmin(): bool
    {
        $user = $this->currentUser->getUser();

        return $user instanceof Admin;
    }

    public function getUser(): UserReference
    {
        $user = $this->currentUser->getUser();

        if (!$user) {
            throw new AuthenticationException('Пользователь не авторизован');
        }

        return new UserReference(
            id: $user->id,
            email: $user->email,
            type: $user->type,
        );
    }
}
