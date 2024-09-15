<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        return response()->json(['cart' => $cart]);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'shipping_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'shipping_type' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'shipping_carrier' => 'required|string',
            'shipping_method' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'estimated_delivery_date' => 'required|date',
        ]);

        // Retrieve the cart from session
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        // Use a database transaction to ensure atomic operations
        DB::transaction(function () use ($validatedData, $cart) {
            // Calculate the cart total and get the seller ID from the first product
            $cartTotal = 0;
            $sellerId = null;

            foreach ($cart as $item) {
                $product = Product::find($item['product']->id); // Fetch the product by its ID

                // Check if the product exists and if the quantity is available
                if (!$product || $product->stock_quantity < $item['quantity']) {
                    throw new \Exception('Product is unavailable or out of stock.');
                }

                if (!$sellerId) {
                    $sellerId = $product->seller_id;  // Assuming the Product model has a `seller_id` field
                }

                $cartTotal += $product->price * $item['quantity'];
            }

            // If no seller ID was found, throw an exception
            if (!$sellerId) {
                throw new \Exception('Seller not found for the products.');
            }

            // Create the order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'seller_id' => $sellerId,
                'order_date' => now(),
                'total_amount' => $cartTotal,
                'is_guest' => false,
                'status' => 'Pending',
            ]);

            // Create the shipping entry
            Shipping::create([
                'order_id' => $order->id,
                'seller_id' => $sellerId,
                'shipping_type' => $validatedData['shipping_type'],
                'shipping_cost' => $validatedData['shipping_cost'],
                'shipping_status' => 'Pending',
                'tracking_number' => null,  // To be generated later
                'shipping_address' => $validatedData['shipping_address'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'postal_code' => $validatedData['postal_code'],
                'country' => $validatedData['country'],
                'shipping_carrier' => $validatedData['shipping_carrier'],
                'shipping_method' => $validatedData['shipping_method'],
                'recipient_name' => $validatedData['recipient_name'],
                'estimated_delivery_date' => $validatedData['estimated_delivery_date'],
            ]);

            // Create order details for each product
            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                ]);
            }

            // Clear the cart after order is placed
            Session::forget('cart');
        });

        return response()->json(['message' => 'Order placed successfully.']);
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
