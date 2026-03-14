<?php

namespace StockFlow\Shared\Kernel\Application\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
