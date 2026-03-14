<?php

namespace StockFlow\Shared\Identity\Domain\Enum;

enum UserType: string
{
    case Admin = 'admin';
    case Manager = 'manager';

    public function isInternal(): bool
    {
        return in_array($this, [self::Admin, self::Manager], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Администратор',
            self::Manager => 'Менеджер',
        };
    }
}
