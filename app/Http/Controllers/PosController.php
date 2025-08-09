<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCardResource;
use App\Models\PointsConversion;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\PosOrderProduct;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::variant()->card()->paginate(20)->withQueryString();
        $pdf_url = session('pdf_url');
        return Inertia::render('Pos/Pos', [
            'products' => ProductCardResource::collection($products),
            'pdf_url' => $pdf_url,
            'vat' => PointsConversion::first()->vat,
            'vat_negation' => PointsConversion::first()->vat_negation
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_phone' => 'required|string|max:15',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.color' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0.01',
        ]);
        $seller = Auth::user();
        $total = collect($request->products)->sum(fn($product) => $product['total']);
        $vat = $total * PointsConversion::first()->vat / 100;
        $vat_negation = $total * PointsConversion::first()->vat_negation / 100;
        $total = $total + $vat - $vat_negation;
        $posOrder = PosOrder::create([
            'seller_name' => $seller->name,
            'seller_phone' => $seller->phone,
            'buyer_phone' => $request->buyer_phone,
            'vat' => $vat,
            'vat_negation' => $vat_negation,
            'total' => $total,
        ]);
        foreach ($request->products as $product) {
            PosOrderProduct::create([
                'pos_order_id' => $posOrder->id,
                'name' => $product['name'],
                'color' => $product['color'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total' => $product['price'] * $product['quantity'],
            ]);
        }
        $settings = SettingService::data();
        $products = $request->products;
        $pdf = PDF::loadView('pdf.pos-invoice', compact('posOrder', 'products', 'settings'));
        $pdf->setPaper([0, 0, 226.77, 609.45]);
        $pdfPath = 'pos-invoices/pos-invoice.pdf';
        $pdf->save(public_path($pdfPath));
        session()->put('pdf_url', asset($pdfPath));
        session()->flash('success', 'Order successful.');
        return Inertia::location(route('pos.index'));
    }
}
