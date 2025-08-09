<?php

namespace Database\Seeders;

use App\Enums\CartEnum;
use App\Enums\OrderStatusEnum;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Number;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        OrderProduct::truncate();
        Payment::truncate();
        $users = User::get();
        $order_products = Sku::with([
            'product' => function ($query) {
                $query->select(
                    'id',
                    'slug',
                    'img',
                    'name',
                    'ref',
                    'thumb',
                    'price',
                    'offer',
                    'old_price',
                    'max_quantity',
                    'color_id',
                    'category_id',
                    'department_id'
                )->with('color:id,name');
            }
        ])->get();
        foreach ($users->multiply(10) as $user) {
            $order_products_selected = $order_products->random(10, 20)->map(function ($sku) {
                $quantity = rand(1, rand(1, 14));
                return OrderService::formatOrderProduct($sku, $quantity);
            });
            $shipping = rand(100, 200);
            $referral_discount = 0;
            $order_data = OrderService::generateOrder($order_products_selected, $shipping, $referral_discount, $user);
            $order_products_selected = $order_products_selected->map(function ($product) {
                unset($product['featured']);
                return $product;
            });
            $order = $order_data['order'];
            $order->data = [
                'user' => $user->only('name', 'address', 'phone', 'email', 'city'),
            ];
            $order->status = fake()->randomElement(OrderStatusEnum::cases());
            if ($order->status != OrderStatusEnum::SUCCESSFUL) {
                $order->refund_at = now();
            }
            $order->total = $order->total + $order->shipping;
            $order->created_at = fake()->dateTimeBetween('-12 months', 'now');
            $order->updated_at = $order->created_at;
            $order->save();
            $order->order_products()->createMany($order_products_selected);
            Payment::factory()->create([
                'order_id' => $order->id
            ]);
            $this->command->info("Order " . $order->code . " : " . Number::currency($order->total));
        }
    }
}
