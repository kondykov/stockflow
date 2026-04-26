<?php

namespace StockFlow\Shared\Identity\Domain\Enum\RBAC;

enum Permission: string
{
    // identity
    case UserCreate = 'user.create';
    case UserEdit = 'user.edit';
    case IdentityAccess = 'identity.access';
    // catalog
    case CatalogCreate = 'catalog.create';
    case CatalogEdit = 'catalog.edit';
    case ProductCreate = 'product.create';
    case ProductEdit = 'product.edit';
    case ProductDelete = 'product.delete';
    // warehouse
    case WarehouseCreate = 'warehouse.create';
    case WarehouseUpdate = 'warehouse.update';
    case WarehouseStockAdjustment = 'warehouse.stock.adjustment';
    case WarehouseStockMovements = 'warehouse.stock.movements';
    case WarehouseStockRemove = 'warehouse.stock.remove';

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
            self::IdentityAccess => 'Доступ к группе страниц "Пользователи"',
            self::WarehouseCreate => 'Регистрация нового склада',
            self::WarehouseStockAdjustment => 'Коррекция остатков',
            self::WarehouseStockMovements => 'Работа с перемещениями (без коррекции остатков)',
            self::WarehouseStockRemove => 'Удаление стока',
        };
    }
}
