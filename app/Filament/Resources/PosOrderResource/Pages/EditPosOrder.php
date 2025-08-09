<?php

namespace App\Filament\Resources\PosOrderResource\Pages;

use App\Filament\Resources\PosOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosOrder extends EditRecord
{
    protected static string $resource = PosOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
