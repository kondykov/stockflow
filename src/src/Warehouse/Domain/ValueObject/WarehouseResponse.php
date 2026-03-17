<?php

namespace StockFlow\Warehouse\Domain\ValueObject;

final readonly class WarehouseResponse
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $address,
        public ?string $createdAt,
        public ?string $updatedAt,
    )
    {
    }
}
