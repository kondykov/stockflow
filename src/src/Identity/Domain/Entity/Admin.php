<?php

namespace StockFlow\Identity\Domain\Entity;

class Admin extends User
{
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function isAdmin(): bool
    {
        return true;
    }
}
