<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Product::class);
        $cart = Session::get('cart', []);
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

        $cart = Session::get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart.']);
    }

    public function update(Request $request, $productId): JsonResponse
    {
        $this->authorize('update', Product::class);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return response()->json(['message' => 'Cart updated.']);
    }

    public function destroy($productId): JsonResponse
    {
        $this->authorize('delete', Product::class);

        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return response()->json(['message' => 'Product removed from cart.']);
    }
}
