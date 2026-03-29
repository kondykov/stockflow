<?php

namespace StockFlow\Shared\Identity\Domain\Enum\RBAC;

enum Permission: string
{
    case UserCreate = 'user.create';
    case UserEdit = 'user.edit';
    case CatalogCreate = 'catalog.create';
    case CatalogEdit = 'catalog.edit';
    case ProductCreate = 'product.create';
    case ProductEdit = 'product.edit';
    case ProductDelete = 'product.delete';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::UserCreate => 'Создание пользователей',
            self::UserEdit => 'Редактирование профилей',
            self::CatalogCreate => 'Создание каталога',
            self::CatalogEdit => 'Редактирование каталога',
            self::ProductCreate => 'Добавление нового продукта',
            self::ProductEdit => 'Редактирование продукта',
            self::ProductDelete => 'Удаление продукта',
        };
    }
}
