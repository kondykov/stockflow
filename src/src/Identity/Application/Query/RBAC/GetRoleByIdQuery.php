<?php

namespace StockFlow\Identity\Application\Query\RBAC;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetRoleByIdQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    ) {
    }
}
