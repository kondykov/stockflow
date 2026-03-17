<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AdjustmentStockCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Идентификатор склада не может быть пустым')]
        #[Assert\Positive(message: 'Идентификатор склада должен быть положительным числом')]
        public int $warehouseId,
        #[Assert\NotBlank(message: 'Идентификатор продукта не может быть пустым')]
        #[Assert\Positive(message: 'Идентификатор продукта должен быть положительным числом')]
        public int $productId,
        #[Assert\NotBlank(message: 'Количество не может быть пустым')]
        #[Assert\Positive(message: 'Количество должно быть положительным числом')]
        public int $quantity,
    ) {
    }
}
