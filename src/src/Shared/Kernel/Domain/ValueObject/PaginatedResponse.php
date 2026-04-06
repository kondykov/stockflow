<?php

namespace StockFlow\Shared\Kernel\Domain\ValueObject;

readonly class PaginatedResponse
{
    public function __construct(
        public int $page,
        public int $perPage,
        public int $totalCount,
        public int $totalPages,
        public bool $hasMorePages,
        public array $items,
    ) {
    }
}
