<?php

namespace StockFlow\Catalog\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateProductCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotNull(message: 'Идентификатор должен быть целым числом')]
        public int $id,

        #[Assert\NotBlank(message: 'Имя продукта не может быть пустым')]
        public string $name,

        #[Assert\NotBlank(message: 'Код артикула не может быть пустым')]
        #[Assert\Length(min: 3, max: 50)]
        public string $skuCode,

        #[Assert\NotBlank(message: 'Название артикула не может быть пустым')]
        public string $skuName,
    ) {
    }
}
