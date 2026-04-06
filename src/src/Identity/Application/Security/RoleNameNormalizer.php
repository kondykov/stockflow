<?php

namespace StockFlow\Identity\Application\Security;

class RoleNameNormalizer
{
    public function normalizeArray(array $roles): array
    {
        $normalized = [];

        foreach ($roles as $role) {
            $normalized[] = $this->normalize($role);
        }

        return $normalized;
    }

    public function normalize(string $role): string
    {
        return ucfirst(strtolower(str_replace('ROLE_', '', $role)));
    }
}
