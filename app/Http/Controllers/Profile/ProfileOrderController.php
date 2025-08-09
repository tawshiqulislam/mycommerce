<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Inertia\Response;

class ProfileOrderController extends Controller
{
    public function orders(): Response
    {
        $user = User::find(Auth::user()->id);
        $orders = $user->orders()->with('payment')->orderBy('id', 'desc')->paginate(10);
        return Inertia::render('Profile/Orders', [
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function orderDetails($code)
    {
        $user = User::find(Auth::user()->id);
        $order = $user->orders()->with('order_products', 'payment', 'refunds')->where('code', $code)->firstOrFail();
        // dd($order->refunds);
        return Inertia::render('Profile/OrderDetails/OrderDetails', [
            'order' => new OrderResource($order),
        ]);
    }

    public function invoicePdf($code)
    {
        $user = User::find(Auth::user()->id);
        $order = $user->orders()->with('order_products', 'payment')->where('code', $code)->firstOrFail();
        $invoice = InvoiceService::generateInvoice($order);
        // return view('pdf.invoice', compact('order'));
        return $invoice->stream();
    }
}