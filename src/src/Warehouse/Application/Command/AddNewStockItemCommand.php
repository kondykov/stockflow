<?php

namespace StockFlow\Warehouse\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddNewStockItemCommand implements CommandInterface
{

    public function __construct(
        #[Assert\NotBlank(message: 'Артикул не может быть пустым')]
        public string $code,
        #[Assert\NotBlank(message: 'Имя не может быть пустым')]
        public string $name,
        public ?string $remoteId = null,
    ) {
    }
}
