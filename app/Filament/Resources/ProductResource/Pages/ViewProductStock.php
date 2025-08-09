<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\OrderProductsRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\SkusRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProductStock extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return "{$this->record->name}";
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->schema([
                ImageEntry::make('thumb')->label('Thumbnail')->circular()->height(80),
                TextEntry::make('name')->label('Name')->columnSpan(2),
                TextEntry::make('ref')->label('Reference'),
                TextEntry::make('color.name')->label('Brand'),
                TextEntry::make('price')->money('BDT')->label('Price'),
            ])->columns(8);
    }

    public function getRelationManagers(): array
    {
        return [
            SkusRelationManager::class,
            OrderProductsRelationManager::class
        ];
    }
}
