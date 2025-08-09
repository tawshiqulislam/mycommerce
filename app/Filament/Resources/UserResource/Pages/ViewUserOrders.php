<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUserOrders extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return "View orders of {$this->record->name}";
    }

    public function getRelationManagers(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->schema([
                TextEntry::make('name')->label('Name'),
                TextEntry::make('email')->label('Email'),
                TextEntry::make('phone')->label('Phone'),
                TextEntry::make('country')->label('Country'),
                TextEntry::make('city')->label('City'),
                TextEntry::make('address')->columnSpanFull()->label('Address'),
            ])->columns(3);
    }
}
