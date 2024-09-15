<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Fetch or create a cart for the user
        $cart = Cart::with('items.product')->where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return response()->json(['cart' => $cart]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cartItem = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product,
        ], [
            'quantity' => $quantity,
        ]);

        if (!$cartItem->wasRecentlyCreated) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        }

        return response()->json(['message' => 'Product added to cart.', 'cart' => $cart->load('items.product')]);
    }

    public function update(Request $request, $cartItemId): JsonResponse
    {
        $user = Auth::user();

        // Fetch the cart item by ID
        $cartItem = CartItem::find($cartItemId);

        // Check if the CartItem exists and belongs to the user
        if (!$cartItem || $cartItem->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized or Cart Item not found'], 403);
        }

        // Validate the incoming request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Update the cart item quantity
        $cartItem->update([
            'quantity' => $request->input('quantity'),
        ]);

        // Reload the cart and its items
        $cart = $cartItem->cart->load('items.product');

        return response()->json(['message' => 'Cart updated.', 'cart' => $cart]);
    }


    public function destroy(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();

        // Ensure the cart item belongs to the user's cart
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart.', 'cart' => $cartItem->cart->load('items.product')]);
    }
}
