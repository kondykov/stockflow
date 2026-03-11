<?php

namespace StockFlow\Identity\Domain\Dto;

use OpenApi\Attributes as OA;

final readonly class PermissionItemResponse
{
    public function __construct(
        #[OA\Property(example: 'user.create')]
        public string $name,
        #[OA\Property(example: 'Создание пользователей')]
        public string $label,
    ) {
    }
}
