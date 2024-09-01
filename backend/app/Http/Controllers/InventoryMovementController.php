<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class InventoryMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-inventory-movement', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventoryMovements = InventoryMovement::all();

        Log::info('User viewed inventory movements.', ['user_id' => Auth::id()]);

        return response()->json($inventoryMovements);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-inventory-movement', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|string|max:255',
        ]);

        $inventoryMovement = InventoryMovement::create($request->all());

        Log::info('Inventory movement created.', ['user_id' => Auth::id(), 'data' => $request->all()]);

        return response()->json($inventoryMovement, 201);
    }

    public function show(InventoryMovement $inventoryMovement): JsonResponse
    {
        if (!Gate::allows('view-inventory-movement', $inventoryMovement)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Log::info('User viewed an inventory movement.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json($inventoryMovement);
    }

    public function update(Request $request, InventoryMovement $inventoryMovement): JsonResponse
    {
        if (!Gate::allows('update-inventory-movement', $inventoryMovement)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|string|max:255',
        ]);

        $inventoryMovement->update($request->all());

        Log::info('Inventory movement updated.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id, 'data' => $request->all()]);

        return response()->json($inventoryMovement);
    }

    public function destroy(InventoryMovement $inventoryMovement): JsonResponse
    {
        if (!Gate::allows('delete-inventory-movement', $inventoryMovement)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventoryMovement->delete();

        Log::info('Inventory movement deleted.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json(null, 204);
    }
}
