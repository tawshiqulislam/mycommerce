<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        DB::table('category_department')->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('products')
                ->whereColumn('products.category_id', 'category_department.category_id')
                ->whereColumn('products.department_id', 'category_department.department_id');
        })->delete();
        return [
            Actions\CreateAction::make(),
        ];
    }
}
