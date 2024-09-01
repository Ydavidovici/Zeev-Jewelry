<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-checkout', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart = Session::get('cart', []);
        return response()->json(['cart' => $cart]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-order', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
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
            return response()->json(['message' => 'Order placed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while placing the order. Please try again.'], 500);
        }
    }

    public function success(): JsonResponse
    {
        return response()->json(['message' => 'Order completed successfully.']);
    }

    public function failure(): JsonResponse
    {
        return response()->json(['message' => 'Order failed.']);
    }
}
