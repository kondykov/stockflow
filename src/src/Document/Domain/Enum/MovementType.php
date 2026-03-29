<?php

namespace StockFlow\Document\Domain\Enum;

enum MovementType: string
{
    case Internal = 'internal';
    case Transfer = 'transfer';
}
