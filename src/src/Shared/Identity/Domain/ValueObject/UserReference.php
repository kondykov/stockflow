<?php

namespace StockFlow\Shared\Identity\Domain\ValueObject;

use StockFlow\Shared\Identity\Domain\Enum\UserType;

readonly class UserReference
{
    public function __construct(
        public int $id,
        public string $email,
        public UserType $type,
    ) {
    }
}
