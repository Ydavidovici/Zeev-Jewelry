<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Only sellers or admins can view inventory
        if (!$user->hasRole('seller') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Admins can view all inventories, sellers can view only their own
        $inventories = $user->hasRole('admin')
            ? Inventory::all()
            : Inventory::where('seller_id', $user->id)->get();

        return response()->json($inventories);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Only sellers or admins can create inventory
        if (!$user->hasRole('seller') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $inventory = new Inventory($request->all());
        $inventory->seller_id = $user->id;
        $inventory->save();

        return response()->json($inventory, 201);
    }

    public function show(Inventory $inventory): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory can view it
        if (!$user->hasRole('admin') && $user->id !== $inventory->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($inventory);
    }

    public function update(Request $request, Inventory $inventory): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory can update it
        if (!$user->hasRole('admin') && $user->id !== $inventory->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $inventory->update($request->all());

        return response()->json($inventory);
    }

    public function destroy(Inventory $inventory): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory can delete it
        if (!$user->hasRole('admin') && $user->id !== $inventory->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventory->delete();

        return response()->json(null, 204);
    }
}
