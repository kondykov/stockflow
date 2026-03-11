<?php

namespace StockFlow\Identity\Domain\Dto;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;


class RoleResponse
{
    public function __construct(
        #[OA\Property(example: 1)]
        public int $id,
        #[OA\Property(example: 'Manager')]
        public string $name,
        #[OA\Property(
            type: 'array',
            items: new OA\Items(ref: new Model(type: PermissionItemResponse::class))
        )]
        public array $permissions,
    )
    {
    }
}
