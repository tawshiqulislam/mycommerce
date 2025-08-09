<?php

namespace App\Filament\Resources\ProductRefundResource\Pages;

use App\Filament\Resources\ProductRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductRefund extends EditRecord
{
    protected static string $resource = ProductRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
