<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartItemController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', CartItem::class);
        return response()->json(CartItem::all());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', CartItem::class);

        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::create($request->all());
        return response()->json($cartItem, 201);
    }

    public function show($id): JsonResponse
    {
        $cartItem = CartItem::findOrFail($id);
        $this->authorize('view', $cartItem);
        return response()->json($cartItem);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $cartItem = CartItem::findOrFail($id);
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($request->all());
        return response()->json($cartItem);
    }

    public function destroy($id): JsonResponse
    {
        $cartItem = CartItem::findOrFail($id);
        $this->authorize('delete', $cartItem);

        $cartItem->delete();
        return response()->json(null, 204);
    }
}
