<?php

namespace StockFlow\Document\Domain\Enum;

enum DocumentType: string
{
    case Returns = 'returns';
    case Inbound = 'inbound';
    case Outbound = 'outbound';
    case Movement = 'movement';
    case Adjustment = 'adjustment';
    case Claim = 'claim';

    public function label(): string
    {
        return match ($this) {
            self::Returns => 'Возврат',
            self::Inbound => 'Поступление',
            self::Outbound => 'Отправка',
            self::Movement => 'Перемещение',
            self::Adjustment => 'Корректировка',
//            self::Claim => 'Претензия / Рекламация',
            default => 'Unknown Document Type'
        };
    }
}
