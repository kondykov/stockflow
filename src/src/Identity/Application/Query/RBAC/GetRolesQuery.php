<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetRolesQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Positive(message: "Номер страницы должен быть положительным")]
        public int $page = 1,
        #[Assert\Positive(message: "Размер страницы должен быть положительным")]
        public int $pageSize = 20,
    ) {
    }
}
