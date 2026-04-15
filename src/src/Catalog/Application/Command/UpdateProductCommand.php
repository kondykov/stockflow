<?php

namespace StockFlow\Catalog\Application\Command;

use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateProductCommand implements CommandInterface
{
    /**
     * @param UploadedFile[] $newImages
     * @param array[] $existingImages
     * @param int[] $deletedImageIds
     */
    public function __construct(
        #[Assert\Positive(message: 'Идентификатор должен быть целым числом')]
        #[OA\Property(example: 1)]
        public int $id,

        #[Assert\NotBlank(message: 'Имя продукта не может быть пустым')]
        #[OA\Property(example: 'Обои Erismann Prime')]
        public string $name,

        #[Assert\NotBlank(message: 'Код артикула не может быть пустым')]
        #[OA\Property(example: 'ER-12345')]
        public string $skuCode,

        #[Assert\NotBlank(message: 'Название артикула не может быть пустым')]
        #[OA\Property(example: 'Винил на флизелине')]
        public string $skuName,

        public ?array $attributes = [],
        public ?array $newImages = [],
        public ?array $existingImages = [],
        public ?array $deletedImageIds = [],
        public ?int $coverIndex = 0,
    ) {
    }
}
