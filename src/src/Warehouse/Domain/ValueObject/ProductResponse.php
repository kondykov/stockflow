<?php

namespace StockFlow\Warehouse\Domain\ValueObject;

final readonly class ProductResponse
{
    public function __construct(
        public ?int $id,
        public string $skuCode,
        public string $skuName,
        public ?string $remoteId,
        public ?string $createdAt,
        public ?string $updatedAt,
    )
    {
    }
}
