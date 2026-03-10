<?php

namespace StockFlow\Shared\Application\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
