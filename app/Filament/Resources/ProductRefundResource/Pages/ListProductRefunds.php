<?php

namespace App\Filament\Resources\ProductRefundResource\Pages;

use App\Filament\Resources\ProductRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductRefunds extends ListRecords
{
    protected static string $resource = ProductRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
