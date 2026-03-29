<?php

namespace StockFlow\Catalog\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductCommand implements CommandInterface
{

    public function __construct(
        #[Assert\NotBlank(message: 'Имя не может быть пустым')]
        public string $name,
        #[Assert\NotBlank(message: 'Артикул не может быть пустым')]
        public string $skuCode,
        #[Assert\NotBlank(message: 'Название артикула не может быть пустым')]
        public string $skuName,
    ) {
    }
}
