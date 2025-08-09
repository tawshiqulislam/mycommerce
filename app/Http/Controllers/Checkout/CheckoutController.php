<?php

namespace App\Http\Controllers\Checkout;

use App\Enums\CartEnum;
use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\PointsConversion;
use App\Models\Shipping;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Rules\ValidateProductRule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckoutController extends Controller
{
    // public function checkout()
    // {
    //     $products = CartService::products(CartEnum::CHECKOUT);
    //     $discountCode = session()->get('discountCode');
    //     $total = OrderService::calculateTotal($products, $discountCode);
    //     // $discount_codes = DiscountCode::whereDate('valid_from', '<=', now())
    //     //     ->whereDate('valid_to', '>=', now())
    //     //     ->where('active', 1)->inRandomOrder()->limit(5)->get();
    //     return Inertia::render('Checkout/Checkout', [
    //         'products' => $products,
    //         'total' => $total,
    //         // 'dicountCodes' => $discount_codes,
    //     ]);
    // }

    public function checkout()
    {
        $products = CartService::products(CartEnum::CHECKOUT);
        $total = OrderService::calculateTotal($products);
        $shippings = Shipping::all()->pluck('area', 'cost');
        $usable_points = User::find(Auth::user()->id)->usable_points;
        $points_conversion = PointsConversion::first();
        $referral_discount = $usable_points * $points_conversion->value;
        $max_percentage = $points_conversion->max_percentage;
        return Inertia::render('Checkout/Checkout', [
            'products' => $products,
            'total' => $total,
            'shippings' => $shippings,
            'referral_discount' => $referral_discount,
            'max_percentage' => $max_percentage
        ]);
    }

    public function addSingleProduct(Request $request)
    {
        $request->validate([
            'skuId' => ['required', 'exists:skus,id', new ValidateProductRule],
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);
        session()->forget(CartEnum::CHECKOUT->value);
        CartService::add(CartEnum::CHECKOUT, $request->skuId, $request->quantity);
        return to_route('checkout');
    }

    public function addShoppingCart()
    {
        $products = CartService::session(CartEnum::SHOPPING_CART);
        session([CartEnum::CHECKOUT->value => $products]);
        return to_route('checkout');
    }
}
