<?php

namespace StockFlow\Identity\Application\Security;

use StockFlow\Identity\Domain\Entity\User;

interface CurrentUserInterface
{
    public function getUser(): ?User;
}
