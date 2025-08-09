<?php

namespace App\Filament\Resources\PointsConversionResource\Pages;

use App\Filament\Resources\PointsConversionResource;
use App\Models\PointsConversion;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointsConversions extends ListRecords
{
    protected static string $resource = PointsConversionResource::class;

    protected function getHeaderActions(): array
    {
        if (PointsConversion::all()->count() == 0) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }
}
