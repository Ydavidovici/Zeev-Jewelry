<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class InventoryMovementController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', InventoryMovement::class);

        $inventoryMovements = InventoryMovement::all();

        Log::info('User viewed inventory movements.', ['user_id' => Auth::id()]);

        return response()->json($inventoryMovements);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', InventoryMovement::class);

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
        $this->authorize('view', $inventoryMovement);

        Log::info('User viewed an inventory movement.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json($inventoryMovement);
    }

    public function update(Request $request, InventoryMovement $inventoryMovement): JsonResponse
    {
        $this->authorize('update', $inventoryMovement);

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
        $this->authorize('delete', $inventoryMovement);
        $inventoryMovement->delete();

        Log::info('Inventory movement deleted.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return response()->json(null, 204);
    }
}
