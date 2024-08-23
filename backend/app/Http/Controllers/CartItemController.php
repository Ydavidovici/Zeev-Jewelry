<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Policy check for viewing any cart items
        $this->authorize('viewAny', CartItem::class);

        // Retrieve all cart items associated with the user's cart
        $cartItems = CartItem::whereHas('cart', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json($cartItems);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Policy check for creating cart items
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
        $user = Auth::user();
        $cartItem = CartItem::findOrFail($id);

        // Policy check for viewing a specific cart item
        $this->authorize('view', $cartItem);

        return response()->json($cartItem);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        $cartItem = CartItem::findOrFail($id);

        // Policy check for updating a specific cart item
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($request->all());

        return response()->json($cartItem);
    }

    public function destroy($id): JsonResponse
    {
        $user = Auth::user();
        $cartItem = CartItem::findOrFail($id);

        // Policy check for deleting a specific cart item
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return response()->json(null, 204);
    }
}
