<?php

namespace StockFlow\Shared\Application\Command;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): mixed;
}
