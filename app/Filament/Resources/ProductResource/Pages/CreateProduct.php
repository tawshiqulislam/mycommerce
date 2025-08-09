<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected ?string $subheading = 'After creating the product, related data can be added.';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['img'] && $data['img'][0] != '/') {
            $data['img'] = '/' . $data['img'];
        }
        if ($data['thumb'] && $data['thumb'][0] != '/') {
            $data['thumb'] = '/' . $data['thumb'];
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['ref'] = now()->format('ymd-His');
        // $data['offer'] = round((($data['old_price'] - $data['price']) / $data['old_price']) * 100);
        if (!empty($data['old_price']) && $data['old_price'] != 0) {
            $data['offer'] = round((($data['old_price'] - $data['price']) / $data['old_price']) * 100);
        } else {
            $data['offer'] = 0; // Default value if old_price is zero or undefined
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
        return static::getModel()::create($data);
    }
}
