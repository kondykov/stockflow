<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteWarehouseCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Positive(message: 'Идентификатор склада должен быть положительным числом')]
        public int $id,
    ) {
    }
}

