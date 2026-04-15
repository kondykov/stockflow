<?php

namespace StockFlow\Catalog\Domain\Dto;

use OpenApi\Attributes as OA;

final readonly class ProductResponse
{
    public function __construct(
        #[OA\Property(example: 1)]
        public int $id,

        #[OA\Property(example: 'Обои Erismann Prime')]
        public string $name,

        #[OA\Property(example: 'ER-123')]
        public string $skuCode,

        #[OA\Property(example: 'Винил на флизелине')]
        public string $skuName,

        /** @var array<array{key: string, value: string}> */
        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'key', type: 'string', example: 'Раппорт'),
                    new OA\Property(property: 'value', type: 'string', example: '64 см')
                ]
            )
        )]
        public array $attributes = [],

        /** @var array<array{url: string, isCover: bool}> */
        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'url', type: 'string', example: '/uploads/products/image.jpg'),
                    new OA\Property(property: 'isCover', type: 'boolean', example: true)
                ]
            )
        )]
        public array $images = [],

        #[OA\Property(example: "2026-03-11T15:00:00+00:00")]
        public ?string $createdAt = null,

        #[OA\Property(example: "2026-03-11T15:10:00+00:00")]
        public ?string $updatedAt = null,
    ) {
    }
}
