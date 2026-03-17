<?php

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetWarehouseByIdQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Positive(message: "Идентификатор должен быть целым числом")]
        public int $id,
    ) {
    }
}
