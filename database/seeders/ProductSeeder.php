<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Department;
use App\Models\Image;
use App\Models\MetaTag;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Product::truncate();
        Image::where('model_type', 'App\Models\Product')->delete();
        $categories = Category::select('id', 'name')->pluck('id', 'name');
        $departments = Department::select('id', 'name')->pluck('id', 'name');
        $products = collect(Storage::json(DatabaseSeeder::getPathProductJson()));
        $colors = Color::pluck('id', 'name');
        $products_variant_array = [];
        $images_array = [];
        $product_count = 1;
        $meta_array = [];
        foreach ($products as $product) {
            $this->command->info($product_count . ' - ' . $product['ref']);
            $product_base = [
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'entry' => $product['entry'],
                'description' => fake()->text(800),
                'max_quantity' => rand(1, 100),
                'featured' => boolval(rand(0, 1000)),
                'department_id' => $departments[$product['department']],
                'category_id' => $categories[$product['category']],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            foreach ($product['variants'] as $variant) {
                $color_id = $colors[$variant['color']['name']];
                $ref = fake()->dateTime()->format('ymd-His');
                $old_price = $product['price'];
                $offer = fake()->randomElement([10, 20, 30, 40, 50]);
                $price = $old_price - ($old_price * ($offer / 100));
                array_push($products_variant_array, [
                    ...$product_base,
                    'id' => $variant['id'],
                    'ref' => $ref,
                    'old_price' => $old_price,
                    'offer' => $offer,
                    'price' => $price,
                    'img' => $variant['img'],
                    'thumb' => $variant['thumb'],
                    'color_id' => $color_id,
                    'created_at' => fake()->dateTimeBetween('-2 days', 'now'),
                    'updated_at' => fake()->dateTimeBetween('-2 days', 'now'),
                ]);
                $product_count++;
                // foreach ($variant['images'] as $key => $image) {
                //     array_push($images_array, [
                //         'img' => $image,
                //         'title' => $product['name'],
                //         'alt' => $product['name'],
                //         'sort' => $key + 1,
                //         'model_type' => 'App\Models\Product',
                //         'model_id' => $variant['id'],
                //     ]);
                // }
                array_push($meta_array, [
                    'meta_title' => $product['name'],
                    'meta_description' => fake()->sentence(),
                    'model_type' => 'App\Models\Product',
                    'model_id' => $variant['id'],
                ]);
            }
        }
        Product::insert($products_variant_array);
        // Image::insert($images_array);
        MetaTag::insert($meta_array);
    }
}
