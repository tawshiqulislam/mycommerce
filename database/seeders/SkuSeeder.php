<?php

namespace Database\Seeders;

use App\Models\Size;
use App\Models\Sku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sku::truncate();
        $products = collect(Storage::json(DatabaseSeeder::getPathProductJson()));
        $sku_id = 1;
        $skus_array = [];
        foreach ($products as $product) {
            foreach ($product['variants'] as $variant) {
                $stock = rand(1, 5) * 12;
                array_push($skus_array, [
                    'id' => $sku_id,
                    'product_id' => $variant['id'],
                    'stock' => $stock,
                    'created_at' => fake()->dateTimeBetween('-2 days', 'now'),
                    'updated_at' => fake()->dateTimeBetween('-2 days', 'now'),
                ]);
                $sku_id++;
            }
            if (count($skus_array) > 200) {
                Sku::insert($skus_array);
                $skus_array = [];
                $this->command->info($sku_id);
            }
        }
        Sku::insert($skus_array);
    }
}
