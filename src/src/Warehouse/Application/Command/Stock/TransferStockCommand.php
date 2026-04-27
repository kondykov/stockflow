<?php

namespace StockFlow\Warehouse\Application\Command\Stock;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TransferStockCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public int $fromWarehouseId,

        #[Assert\NotBlank]
        #[Assert\NotEqualTo(propertyPath: 'fromWarehouseId', message: 'Склады должны различаться')]
        public int $toWarehouseId,

        #[Assert\NotBlank]
        public int $stockId,

        #[Assert\NotBlank]
        #[Assert\GreaterThan(0, message: 'Количество должно быть больше нуля')]
        public int $quantity,

        public ?string $reason = null
    ) {
    }
}
