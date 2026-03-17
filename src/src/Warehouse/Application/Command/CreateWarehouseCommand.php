<?php

namespace StockFlow\Warehouse\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateWarehouseCommand implements CommandInterface
{

    public function __construct(
        #[Assert\Length(
            min: 1,
            max: 80,
            minMessage: "Название склада должно быть не менее {{ limit }} символов",
            maxMessage: "Название склада должно быть не более {{ limit }} символов"
        )]
        #[Assert\NotBlank(message: 'Название склада не может быть пустым')]
        public string $name,
        #[Assert\NotBlank(message: 'Адрес склада не может быть пустым')]
        public string $address,
    ) {
    }
}
