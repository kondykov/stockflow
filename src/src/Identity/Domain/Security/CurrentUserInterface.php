<?php

namespace StockFlow\Identity\Domain\Security;

use StockFlow\Identity\Domain\Entity\User;

interface CurrentUserInterface
{
    public function getUser(): ?User;
}
