<?php

namespace StockFlow\Shared\Kernel\Application\Extractor;

use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;

class PaginationExtractor
{
    public function extract(PaginatedResponse $data, ExtractorInterface $extractor): array
    {
        $response = [
            'page' => $data->page,
            'perPage' => $data->perPage,
            'totalCount' => $data->totalCount,
            'totalPages' => $data->totalPages,
            'hasMorePages' => $data->hasMorePages,
        ];

        foreach ($data->items as $entity) {
            $response['items'][] = $extractor->extract($entity);
        }

        return $response;
    }
}
