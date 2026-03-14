<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Security\Voter;

use StockFlow\Identity\Domain\Entity\Admin;
use StockFlow\Identity\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class PermissionVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return str_contains($attribute, '.');
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($user instanceof Admin) {
            return true;
        }

        foreach ($user->userRoles as $role) {
            foreach ($role->permissions as $permissionEntity) {
                if ($permissionEntity->name->value === $attribute) {
                    return true;
                }
            }
        }

        return false;
    }
}
