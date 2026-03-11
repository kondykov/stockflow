<?php

namespace StockFlow\Identity\Domain\ValueObject\RBAC;

enum Permission: string
{
    case USER_CREATE = 'user.create';
    case USER_EDIT = 'user.edit';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::USER_CREATE => 'Создание пользователей',
            self::USER_EDIT => 'Редактирование профилей',
        };
    }
}
