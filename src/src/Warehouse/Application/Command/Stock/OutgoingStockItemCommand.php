<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class OutgoingStockItemCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Идентификатор склада не может быть пустым')]
        #[Assert\Positive(message: 'Идентификатор склада должен быть положительным числом')]
        public int $warehouseId,
        #[Assert\NotBlank(message: 'Идентификатор позиции товара не может быть пустым')]
        #[Assert\Positive(message: 'Идентификатор позиции товара должен быть положительным числом')]
        public int $stockItemId,
        #[Assert\NotBlank(message: 'Количество не может быть пустым')]
        #[Assert\Positive(message: 'Количество должно быть положительным числом')]
        public int $quantity,
    ) {
    }
}
