<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Sku;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\PointsConversion;

class OrderService
{
    public static function generateCode($id): string
    {
        // $week = strtoupper(Str::slug(now()->isoFormat('dd')));
        $week = strtoupper(Str::slug(now()->subDays(rand(1, 7))->isoFormat('dd')));
        return $week . Str::padLeft($id . fake()->bothify('###'), 6, '0');
    }

    public static function subtotal($products): float
    {
        $sub_total = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });
        return $sub_total;
    }

    public static function generateOrder(Collection $order_products, $shipping, $referral_discount, $user): array
    {
        $user = $user ?? Auth::user();
        $order_array = self::calculateTotal($order_products);
        $order_array['total'] += $shipping;
        $order_array['total'] -= $referral_discount;
        $referrer_points = $order_products->filter(function ($product) {
            return $product['featured'] == 1;
        })->sum(function ($product) {
            return round($product['price'] * 0.02 * $product['quantity']);
        });
        $user->referrer_points += $referrer_points;
        $points_conversion = PointsConversion::first();
        $user->used_points += ($referral_discount / $points_conversion->value) * $points_conversion->points;
        $user->usable_points = $user->points - $user->used_points;
        $user->save();
        $order = new Order([
            ...$order_array,
            'referral_discount' => $referral_discount,
            'shipping' => $shipping,
            'quantity' => $order_products->sum('quantity'),
            'code' => self::generateCode($user->id),
            'user_id' => $user->id,
        ]);
        return ['order' => $order, 'referrer_points_added' => $referrer_points];
    }

    // public static function calculateTotal($products, $discountCode = null): array
    // {
    //     $subtotal = $products->sum('total');
    //     $taxRate = SettingService::data()['rates']['tax'];
    //     $shipping = (float) SettingService::data()['rates']['shipping'];
    //     $freeShipping = (float) SettingService::data()['rates']['freeShipping'];
    //     if ($discountCode) {
    //         $discountValue = $discountCode->calculateDiscount($subtotal);
    //         $discountCode->applied = $discountValue;
    //     } else {
    //         $discountValue = 0;
    //     }
    //     $subtotalWithDiscount = round($subtotal - $discountValue, 2);
    //     $tax = round($subtotalWithDiscount * ($taxRate / 100), 2);
    //     $subtotalWithTaxes = ($subtotalWithDiscount + $tax);
    //     if ($subtotalWithTaxes > $freeShipping) {
    //         $shipping = 0;
    //     }
    //     $total = round($subtotalWithTaxes + $shipping, 2);
    //     return [
    //         'sub_total' => $subtotal,
    //         'discount' => $discountCode,
    //         'tax_rate' => $taxRate,
    //         'tax_value' => $tax,
    //         'shipping' => $shipping,
    //         'total' => $total,
    //     ];
    // }

    public static function calculateTotal($products): array
    {
        $subtotal = $products->sum('total');
        return [
            'sub_total' => $subtotal,
            'total' => $subtotal,
        ];
    }

    public static function formatOrderProduct($sku, $quantity)
    {
        $product = $sku->product;
        return [
            ...$product->only([
                'name',
                'ref',
                'thumb',
                'old_price',
                'offer',
                'price',
                'category_id',
                'department_id',
                'featured',
            ]),
            'color' => $product->color->name,
            'total' => round($product->price * $quantity, 2),
            'quantity' => $quantity,
            'sku_id' => $sku->id,
            'product_id' => $product->id,
        ];
    }

    public static function generate_order_products_checkout(array $products): Collection
    {
        $skuIds = array_keys($products);
        return Sku::with('product')
            ->find($skuIds)
            ->filter(function ($sku) use ($products) {
                return $sku->stock >= $products[$sku->id];
            })
            ->map(function ($sku) use ($products) {
                $quantity = $products[$sku->id];
                return self::formatOrderProduct($sku, $quantity);
            });
    }
}
