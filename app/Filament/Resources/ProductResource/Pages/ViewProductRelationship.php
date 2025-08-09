<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\SpecificationsRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProductRelationship extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getBreadcrumb(): string
    {
        return 'Releted data';
    }
    public function getTitle(): string
    {
        return "{$this->record->name}";
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->schema([
                ImageEntry::make('thumb')->label('Thumbnail'),
                TextEntry::make('name')->label('Name')->columnSpan(3),
                TextEntry::make('ref')->label('Reference'),
                TextEntry::make('color.name')->label('Unit'),
                TextEntry::make('price')->money('BDT')->label('Price'),
            ])->columns(8);
    }

    public function getRelationManagers(): array
    {
        return [
            ImagesRelationManager::class,
            SpecificationsRelationManager::class,

        ];
    }
}
