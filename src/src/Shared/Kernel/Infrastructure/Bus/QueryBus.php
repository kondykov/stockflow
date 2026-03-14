<?php

namespace StockFlow\Shared\Kernel\Infrastructure\Bus;

use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $queryBus,
    )
    {
        $this->messageBus = $queryBus;
    }

    public function execute(QueryInterface $query): mixed
	{
		return $this->handle($query);
	}
}
