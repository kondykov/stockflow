<?php

namespace StockFlow\Catalog\Domain\Dto;

use OpenApi\Attributes as OA;

final readonly class ProductResponse
{
    public function __construct(
        #[OA\Property(example: 1)]
        public int $id,

        #[OA\Property(example: 'Product Name')]
        public string $name,

        #[OA\Property(example: 'SKU123')]
        public string $sku,

        #[OA\Property(example: "2026-03-11T15:00:00+00:00")]
        public string $createdAt,

        #[OA\Property(example: "2026-03-11T15:10:00+00:00")]
        public string $updatedAt,
    ) {}
}
