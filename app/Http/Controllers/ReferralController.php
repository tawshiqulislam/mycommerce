<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Models\OrderProduct;
use App\Models\PointsConversion;
use App\Models\ProductRefund;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /**
     * Display the user's referrals.
     */
    public function referrals()
    {
        $user = User::find(Auth::user()->id);
        $referrals = User::where('referrer_code', operator: $user->referral_code)->get();
        $user->points = $referrals->sum('referrer_points');
        $user->usable_points = $user->points - $user->used_points;
        $user->save();
        $points_conversion = PointsConversion::first();
        $points_value = $points_conversion ? $points_conversion->value : 0;
        $referral_reward = $user->usable_points * $points_value;
        return Inertia::render('Profile/Referrals', [
            'referrals' => $referrals,
            'referral_code' => $user->referral_code,
            'referral_reward' => $referral_reward,
            'used_points' => $user->used_points,
            'usable_points' => $user->usable_points
        ]);
    }

    public function processRefund($code, $productId)
    {
        // Validate inputs
        $validator = Validator::make([
            'code' => $code,
            'productId' => $productId,
        ], [
            'code' => 'required|string',
            'productId' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Fetch authenticated user
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $orders = $user->orders()->with('order_products', 'payment')->where('code', $code)->firstOrFail();

        // Fetch order and product
        $singleProductOrder = OrderProduct::find($productId);
        $singleOrder = Order::where('code', $code)->where('user_id', $user->id)->first();

        // Validate order and product existence
        if (!$singleProductOrder || !$singleOrder) {
            return redirect()->back()->with('error', 'Order or product not found');
        }

        // Check if a refund already exists for this product
        if (ProductRefund::where('order_product_id', $singleProductOrder->id)->exists()) {
            return redirect()->back()->with('error', 'Refund already requested for this product');
        }

        // Calculate refund points
        $refundPoint = (int)ceil(($singleProductOrder->price * $singleProductOrder->quantity) * 0.02);

        // Ensure the user has enough points
        if ($user->referrer_points < $refundPoint) {
            return redirect()->back()->with('error', 'Insufficient points to process refund');
        }

        // Begin a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Create the refund
            $refund = ProductRefund::create([
                'order_code' => $singleOrder->code,
                'order_id' => $singleOrder->id,
                'order_product_id' => $singleProductOrder->id,
                'price' => $singleProductOrder->price,
                'quantity' => $singleProductOrder->quantity,
                'user_id' => $user->id,
                'status' => 0, // Customer-requested refund
                'point' => $refundPoint,
                'note' => '',
            ]);

            // Deduct points from the user
            $user->referrer_points -= $refundPoint;
            $user->save();

            // Commit the transaction
            DB::commit();

            // Log the successful refund request
            \Log::info('Refund request processed successfully', [
                'order_code' => $singleOrder->code,
                'product_id' => $singleProductOrder->id,
                'user_id' => $user->id,
            ]);
            return redirect()->back()->with('success', 'Refund request submitted successfully and point refunded.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error
            \Log::error('Refund process failed', [
                'error' => $e->getMessage(),
                'order_code' => $code,
                'product_id' => $productId,
                'user_id' => $user->id,
            ]);

            return redirect()->back()->with('error', 'Refund process failed');
        }
    }
}
