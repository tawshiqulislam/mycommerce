<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethodEnum: string  implements HasLabel, HasColor, HasIcon
{
    case BKASH = 'bkash';
    case NAGAD = 'nagad';
    case SSL = 'ssl';
    case COD = 'cash-on-delivery';

    public function getColor(): string
    {
        return match ($this) {
            PaymentMethodEnum::BKASH => 'gray',
            PaymentMethodEnum::NAGAD => 'gray',
            PaymentMethodEnum::SSL => 'gray',
            PaymentMethodEnum::COD => 'gray',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            PaymentMethodEnum::BKASH => 'Bkash',
            PaymentMethodEnum::NAGAD => 'Nagad',
            PaymentMethodEnum::SSL => 'SSL',
            PaymentMethodEnum::COD => 'Cash',
        };
    }
    public function getIcon(): string
    {
        return match ($this) {
            PaymentMethodEnum::BKASH => '',
            PaymentMethodEnum::NAGAD => '',
            PaymentMethodEnum::SSL => '',
            PaymentMethodEnum::COD => '',
        };
    }
}
