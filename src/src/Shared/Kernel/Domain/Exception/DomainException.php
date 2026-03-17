<?php

namespace StockFlow\Shared\Kernel\Domain\Exception;

use Exception;
use Throwable;

class DomainException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
