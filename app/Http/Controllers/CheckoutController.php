<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->address = $validatedData['address'];
            $order->city = $validatedData['city'];
            $order->postal_code = $validatedData['postal_code'];
            $order->status = 'Pending';
            $order->save();

            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                ]);
            }

            Session::forget('cart');
            return redirect()->route('checkout.success')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('checkout.failure')->with('error', 'An error occurred while placing the order. Please try again.');
        }
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function failure()
    {
        return view('checkout.failure');
    }
}
