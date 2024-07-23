<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;

class SellerController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', auth()->id())->get();
        $orders = Order::where('seller_id', auth()->id())->get();
        $inventory = Inventory::where('seller_id', auth()->id())->get();
        $shipping = Shipping::where('seller_id', auth()->id())->get();
        $payments = Payment::where('seller_id', auth()->id())->get();

        return view('seller-page.dashboard', compact('products', 'orders', 'inventory', 'shipping', 'payments'));
    }
}
