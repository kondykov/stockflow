<?php

namespace StockFlow\Shared\Kernel\Infrastructure\Bus;

use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $commandBus,
    )
    {
        $this->messageBus = $commandBus;
    }

	public function execute(CommandInterface $command): mixed
	{
        return $this->handle($command);
	}
}
