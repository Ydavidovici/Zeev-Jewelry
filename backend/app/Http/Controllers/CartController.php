<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $cartId = $request->cookie('cart_id');

        if (!$cartId) {
            $cartId = Str::uuid();
            cookie()->queue(cookie('cart_id', $cartId, 60 * 24 * 30)); // 30 days
        }

        $cart = Session::get("cart_{$cartId}", []);

        return response()->json(['cart' => $cart]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity', 1);

        $cartId = $request->cookie('cart_id');

        if (!$cartId) {
            $cartId = Str::uuid();
            cookie()->queue(cookie('cart_id', $cartId, 60 * 24 * 30)); // 30 days
        }

        $cart = Session::get("cart_{$cartId}", []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        Session::put("cart_{$cartId}", $cart);

        return response()->json(['message' => 'Product added to cart.']);
    }

    public function update(Request $request, $productId): JsonResponse
    {
        $this->authorize('update', Product::class);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartId = $request->cookie('cart_id');
        $cart = Session::get("cart_{$cartId}", []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->input('quantity');
            Session::put("cart_{$cartId}", $cart);
        }

        return response()->json(['message' => 'Cart updated.']);
    }

    public function destroy(Request $request, $productId): JsonResponse
    {
        $this->authorize('delete', Product::class);

        $cartId = $request->cookie('cart_id');
        $cart = Session::get("cart_{$cartId}", []);

        unset($cart[$productId]);
        Session::put("cart_{$cartId}", $cart);

        return response()->json(['message' => 'Product removed from cart.']);
    }
}
