<?php

namespace App\Filament\Resources\PointsConversionResource\Pages;

use App\Filament\Resources\PointsConversionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPointsConversion extends EditRecord
{
    protected static string $resource = PointsConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
