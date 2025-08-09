<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['img'] && $data['img'][0] != '/') {
            $data['img'] = '/' . $data['img'];
        }
        if ($data['thumb'] && $data['thumb'][0] != '/') {
            $data['thumb'] = '/' . $data['thumb'];
        }
        $exists = DB::table('category_department')
            ->where('department_id', $data['department_id'])
            ->where('category_id', $data['category_id'])
            ->exists();
        if (!$exists) {
            DB::table('category_department')->insert([
                'department_id' => $data['department_id'],
                'category_id' => $data['category_id']
            ]);
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->before(function ($record) {
                foreach ($record->skus as $sku) {
                    $sku->stock_adjustments()->delete();
                }
                $record->skus()->delete();
            }),
        ];
    }
}
