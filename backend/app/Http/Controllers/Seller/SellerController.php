<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        if (!Gate::allows('view-dashboard-seller', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $products = Product::where('seller_id', $user->id)->get();
        $orders = Order::where('seller_id', $user->id)->get();
        $inventory = Inventory::where('seller_id', $user->id)->get();
        $shipping = Shipping::where('seller_id', $user->id)->get();
        $payments = Payment::where('seller_id', $user->id)->get();

        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'inventory' => $inventory,
            'shipping' => $shipping,
            'payments' => $payments,
        ]);
    }
}
