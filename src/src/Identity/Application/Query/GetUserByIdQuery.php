<?php

namespace StockFlow\Identity\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;


class GetUserByIdQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Positive(message: 'ID должен быть положительным числом')]
        public int $id,
    ) {
    }
}
