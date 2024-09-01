<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (!Gate::allows('view-any-cart', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart = Cart::with('items.product')->where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return response()->json(['cart' => $cart]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!Gate::allows('create-cart', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity');

        $cartItem = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ], [
            'quantity' => $quantity,
        ]);

        if (!$cartItem->wasRecentlyCreated) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        }

        return response()->json(['message' => 'Product added to cart.', 'cart' => $cart->load('items.product')]);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();

        if (!Gate::allows('update-cart', $cartItem->cart, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update([
            'quantity' => $request->input('quantity'),
        ]);

        return response()->json(['message' => 'Cart updated.', 'cart' => $cartItem->cart->load('items.product')]);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        $user = Auth::user();

        if (!Gate::allows('delete-cart', $cartItem->cart, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart.', 'cart' => $cartItem->cart->load('items.product')]);
    }
}
