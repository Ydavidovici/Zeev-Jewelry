<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewDashboard', auth()->user());

        $orders = Order::where('seller_id', auth()->id())->count();
        $products = Product::where('seller_id', auth()->id())->count();
        $payments = Payment::where('seller_id', auth()->id())->count();
        $shippings = Shipping::where('seller_id', auth()->id())->count();

        return response()->json([
            'order_count' => $orders,
            'product_count' => $products,
            'payment_count' => $payments,
            'shipping_count' => $shippings,
        ]);
    }
}
