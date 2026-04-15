<?php

namespace StockFlow\Catalog\Application\Command;

use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductCommand implements CommandInterface
{
    /**
     * @param array[] $attributes
     * @param array[] $images массив с 'file' (UploadedFile) и 'isCover' (bool)
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Имя не может быть пустым')]
        #[OA\Property(example: 'Обои Erismann Prime')]
        public string $name,

        #[Assert\NotBlank(message: 'Код SKU обязателен')]
        #[OA\Property(example: 'ER-12345')]
        public string $skuCode,

        #[Assert\NotBlank(message: 'Название SKU обязательно')]
        #[OA\Property(example: 'Винил на флизелине')]
        public string $skuName,

        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'key', type: 'string'),
                    new OA\Property(property: 'value', type: 'string')
                ]
            )
        )]
        public ?array $attributes = [],

        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'file', type: 'string', format: 'binary'),
                    new OA\Property(property: 'isCover', type: 'boolean')
                ]
            )
        )]
        public ?array $images = [],

        #[OA\Property(example: 0)]
        public ?int $coverImageIndex = 0,
    ) {
    }
}
