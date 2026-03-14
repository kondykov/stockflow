<?php

namespace StockFlow\Shared\Kernel\Application\Command;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): mixed;
}
