<?php

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetAllStocksQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Positive(message: "Идентификатор должен быть целым числом")]
        public int $id,
        #[Assert\Positive(message: "Номер страницы должен быть положительным")]
        public int $page = 1,
        #[Assert\Positive(message: "Размер страницы должен быть положительным")]
        public int $pageSize = 20,
    ) {
    }
}
