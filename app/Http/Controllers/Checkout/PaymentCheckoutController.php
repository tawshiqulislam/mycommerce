<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Auth\OtpController;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Auth;
use App\Enums\CartEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUserRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Sku;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;
use Raziul\Sslcommerz\Facades\Sslcommerz;
use Inertia\Inertia;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PaymentCheckoutController extends Controller
{
    public function purchase(OrderUserRequest $request)
    {
        $rules = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric'
        ]);

        $updatedRequestData = $request->all();
        if (strlen($updatedRequestData['phone']) == 11) {
            $updatedRequestData['phone'] = '88' . $updatedRequestData['phone'];
            $request->merge(['phone' => $updatedRequestData['phone']]);
        }

        $user = User::find(Auth::user()->id);
        $shipping = session()->get('shipping');
        $referral_discount = session()->get('referral_discount') ?? 0;
        $products = CartService::session(CartEnum::CHECKOUT);
        $order_products = OrderService::generate_order_products_checkout($products);
        $order_data = OrderService::generateOrder($order_products, $shipping, $referral_discount, $user);
        $order = $order_data['order'];
        $referrer_points_added = $order_data['referrer_points_added'];
        $order_products = $order_products->map(function ($product) {
            unset($product['featured']);
            return $product;
        });
        // dd($request->all(), $request->phone);
        DB::transaction(function () use ($order, $order_products, $request) {
            $order->status = OrderStatusEnum::PENDING;
            $order->data = [
                'user' => $request->all()
            ];
            $order->save();
            $order->order_products()->createMany($order_products);
            Payment::factory()->create([
                'order_id' => $order->id,
                'method' => PaymentMethodEnum::COD->value
            ]);
            session()->forget(CartEnum::CHECKOUT->value);
            session()->forget(CartEnum::SHOPPING_CART->value);
            session()->forget('shipping');
            session()->forget('referral_discount');
        });
        $point_word = $referrer_points_added > 1 ? 'points' : 'point';
        $message = "{$referrer_points_added} referral {$point_word} sent to your referrer!";
        $this->send_sms($order, 'pending');
        $this->send_email($order, 'pending');
        return to_route('profile.order', $order->code)->with(['success' => $message]);
    }

    public function ssl_purchase(OrderUserRequest $request)
    {
        $rules = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric'
        ]);
        $user = User::find(Auth::user()->id);
        $shipping = session()->get('shipping');
        $referral_discount = session()->get('referral_discount') ?? 0;
        $products = CartService::session(CartEnum::CHECKOUT);
        $order_products = OrderService::generate_order_products_checkout($products);
        $order_data = OrderService::generateOrder($order_products, $shipping, $referral_discount, $user);
        $order = $order_data['order'];
        $order_products = $order_products->map(function ($product) {
            unset($product['featured']);
            return $product;
        });
        DB::transaction(function () use ($order, $order_products, $request) {
            $order->status = OrderStatusEnum::PENDING;
            $order->data = [
                'user' => $request->all()
            ];
            $order->save();
            $order->order_products()->createMany($order_products);
            Payment::factory()->create([
                'order_id' => $order->id,
                'method' => PaymentMethodEnum::SSL->value
            ]);
            session()->forget(CartEnum::CHECKOUT->value);
            session()->forget(CartEnum::SHOPPING_CART->value);
            session()->forget('shipping');
            session()->forget('referral_discount');
        });
        $response = Sslcommerz::setOrder($order->total, $order->id, 'KhorochPati')
            ->setCustomer($request->name, $request->email, $request->phone)
            ->setShippingInfo($order->quantity, $request->address)
            ->makePayment();
        if ($response->success()) {
            return Inertia::location($response->gatewayPageURL());
        } else {
            return to_route('checkout')->with(['error' => 'SSL payment failed!']);
        }
    }

    public function ssl_success(Request $request)
    {
        return "ssl_success";
        // $payment = Sslcommerz::validate($request);
        // if ($payment->status === 'VALID') {
        //     $order = Order::find($payment->order_id);
        //     $order->update(['status' => OrderStatusEnum::PENDING]);
        //     Session::put('payment', 'success');
        //     return to_route('/profile/my-orders')->with(['success' => 'SSL payment successful!']);
        // }
        // return to_route('ssl.failure');
        return redirect()->route('profile.orders')->with(['success' => 'SSL payment successful!']);
    }

    public function ssl_failure()
    {
        return to_route('profile.orders')->with(['error' => 'SSL payment failed!']);
    }

    public function send_sms($order, $status)
    {
        try {
            // Ensure the phone number exists in the order data
            $phone = $order->data?->user?->phone;

            if (empty($phone)) {
                \Log::warning("No phone number found for order #{$order->code}");
                return;
            }

            // Send SMS using the OtpController's sendOrderStatusSMS method
            $response = app(OtpController::class)->sendOrderStatusSMS($phone, $order->code, $status);

            // Log the SMS API response
            if ($response['success'] ?? false) {
                \Log::info("SMS sent successfully for order #{$order->code} to {$phone}");
            } else {
                \Log::error("Failed to send SMS for order #{$order->code}: " . ($response['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send SMS for order #{$order->code}: " . $e->getMessage());
        }
    }
    public function send_email($order, $status)
    {
        // $order = Order::with('order_products')->first();
        // dd($order->data?->user?->email, $order);
        // Validate status
        if (!in_array($status, ['pending', 'shipped', 'delivered', 'successful', 'refunded', 'canceled'])) {
            return "Invalid status!";
        }

        // Validate $order->data structure
        if (!isset($order->data->user->email)) {
            return "Invalid order data!";
        }

        // Send email
        Mail::to($order->data->user->email)->send(new OrderStatusMail($order, $status));

        return "Email sent for status: {$status}";
    }
}
