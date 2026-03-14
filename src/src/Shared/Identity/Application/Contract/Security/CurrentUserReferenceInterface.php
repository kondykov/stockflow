<?php

namespace StockFlow\Shared\Identity\Application\Contract\Security;

use StockFlow\Shared\Identity\Domain\ValueObject\UserReference;

interface CurrentUserReferenceInterface
{
    public function getUser(): UserReference;
    public function isAdmin(): bool;
}
