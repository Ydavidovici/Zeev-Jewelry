<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class InventoryMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Only sellers or admins can view inventory movements
        if (!$user->hasRole('seller') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // For sellers, only show inventory movements related to their products
        if ($user->hasRole('seller')) {
            $inventoryMovements = InventoryMovement::whereHas('inventory.product', function ($query) use ($user) {
                $query->where('seller_id', $user->id);
            })->get();
        } else {
            // Admins can see all inventory movements
            $inventoryMovements = InventoryMovement::all();
        }

        Log::info('User viewed inventory movements.', ['user_id' => $user->id]);

        return response()->json($inventoryMovements);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Only sellers or admins can create inventory movements
        if (!$user->hasRole('seller') && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'movement_type' => 'required|string|max:255',
            'quantity_change' => 'required|integer',
            'movement_date' => 'required|date',
        ]);

        // Check if the inventory belongs to the seller
        $inventory = \App\Models\Inventory::find($request->inventory_id);
        $sellerId = $inventory->product->seller_id;

        if (!$user->hasRole('admin') && $user->id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventoryMovement = InventoryMovement::create($request->all());

        Log::info('Inventory movement created.', ['user_id' => $user->id, 'data' => $request->all()]);

        return response()->json($inventoryMovement, 201);
    }

    public function show(InventoryMovement $inventoryMovement): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory movement can view it
        $sellerId = $inventoryMovement->inventory->product->seller_id;

        if (!$user->hasRole('admin') && $user->id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Log::info('User viewed an inventory movement.', ['user_id' => $user->id, 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json($inventoryMovement);
    }

    public function update(Request $request, InventoryMovement $inventoryMovement): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory movement can update it
        $sellerId = $inventoryMovement->inventory->product->seller_id;

        if (!$user->hasRole('admin') && $user->id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'movement_type' => 'required|string|max:255',
            'quantity_change' => 'required|integer',
            'movement_date' => 'required|date',
        ]);

        $inventoryMovement->update($request->all());

        Log::info('Inventory movement updated.', ['user_id' => $user->id, 'inventory_movement_id' => $inventoryMovement->id, 'data' => $request->all()]);

        return response()->json($inventoryMovement);
    }

    public function destroy(InventoryMovement $inventoryMovement): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller who owns the inventory movement can delete it
        $sellerId = $inventoryMovement->inventory->product->seller_id;

        if (!$user->hasRole('admin') && $user->id !== $sellerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventoryMovement->delete();

        Log::info('Inventory movement deleted.', ['user_id' => $user->id, 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json(null, 204);
    }
}
