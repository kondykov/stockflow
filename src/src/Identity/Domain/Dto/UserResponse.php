<?php

namespace StockFlow\Identity\Domain\Dto;

use OpenApi\Attributes as OA;

final readonly class UserResponse
{
    public function __construct(
        #[OA\Property(example: 1)]
        public int $id,

        #[OA\Property(example: 'user@test.com')]
        public string $email,

        /** @var string[] */
        #[OA\Property(example: ['ROLE_USER'])]
        public array $roles,

        #[OA\Property(example: "2026-03-11T15:00:00+00:00")]
        public string $createdAt,

        #[OA\Property(example: "2026-03-11T15:10:00+00:00")]
        public string $updatedAt,
    ) {}
}
