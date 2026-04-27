<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateWarehouseCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Positive(message: 'Идентификатор должен быть целым числом')]
        public int $id,
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
