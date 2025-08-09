<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: string implements HasLabel, HasColor, HasIcon
{
    case CANCELLED = 'canceled';
    case REFUNDED = 'refunded';
    case SUCCESSFUL = 'successful';
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case SHIPPED = 'shipped';

    public function getLabel(): string
    {
        return match ($this) {
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
            self::SUCCESSFUL => 'Successful',
            self::PENDING => 'Pending',
            self::DELIVERED => 'Delivered',
            self::SHIPPED => 'Shipped',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CANCELLED => 'gray',
            self::REFUNDED => 'danger',
            self::SUCCESSFUL => 'success',
            self::PENDING => 'gray',
            self::DELIVERED => 'success',
            self::SHIPPED => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::REFUNDED => 'heroicon-m-receipt-refund',
            self::SUCCESSFUL => 'heroicon-m-check-circle',
            self::CANCELLED => 'heroicon-m-x-circle',
            self::PENDING => 'heroicon-m-check-circle',
            self::DELIVERED => 'heroicon-m-check-circle',
            self::SHIPPED => 'heroicon-m-truck',
        };
    }
}
