<?php

namespace StockFlow\Shared\Identity\Domain\Enum\RBAC;

enum Permission: string
{
    case UserCreate = 'user.create';
    case UserEdit = 'user.edit';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::UserCreate => 'Создание пользователей',
            self::UserEdit => 'Редактирование профилей',
        };
    }
}
