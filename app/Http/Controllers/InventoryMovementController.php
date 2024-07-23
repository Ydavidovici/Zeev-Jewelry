<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', InventoryMovement::class);

        $inventoryMovements = InventoryMovement::all();

        // For logging purposes
        Log::info('User viewed inventory movements.', ['user_id' => Auth::id()]);

        return view('inventory_movements.index', compact('inventoryMovements'));
    }

    public function create()
    {
        $this->authorize('create', InventoryMovement::class);
        return view('inventory_movements.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryMovement::class);

        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|string|max:255',
        ], [
            'inventory_id.required' => 'The inventory ID is required.',
            'inventory_id.exists' => 'The selected inventory ID does not exist.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'movement_type.required' => 'The movement type is required.',
            'movement_type.string' => 'The movement type must be a string.',
            'movement_type.max' => 'The movement type may not be greater than 255 characters.',
        ]);

        InventoryMovement::create($request->all());

        // Log the creation of the inventory movement
        Log::info('Inventory movement created.', ['user_id' => Auth::id(), 'data' => $request->all()]);

        return redirect()->route('inventory-movements.index')->with('success', 'Inventory movement created successfully.');
    }

    public function show(InventoryMovement $inventoryMovement)
    {
        $this->authorize('view', $inventoryMovement);

        // Log the viewing of the specific inventory movement
        Log::info('User viewed an inventory movement.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return view('inventory_movements.show', compact('inventoryMovement'));
    }

    public function edit(InventoryMovement $inventoryMovement)
    {
        $this->authorize('update', $inventoryMovement);
        return view('inventory_movements.edit', compact('inventoryMovement'));
    }

    public function update(Request $request, InventoryMovement $inventoryMovement)
    {
        $this->authorize('update', $inventoryMovement);

        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|string|max:255',
        ], [
            'inventory_id.required' => 'The inventory ID is required.',
            'inventory_id.exists' => 'The selected inventory ID does not exist.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'movement_type.required' => 'The movement type is required.',
            'movement_type.string' => 'The movement type must be a string.',
            'movement_type.max' => 'The movement type may not be greater than 255 characters.',
        ]);

        $inventoryMovement->update($request->all());

        // Log the update of the inventory movement
        Log::info('Inventory movement updated.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id, 'data' => $request->all()]);

        return redirect()->route('inventory-movements.index')->with('success', 'Inventory movement updated successfully.');
    }

    public function destroy(InventoryMovement $inventoryMovement)
    {
        $this->authorize('delete', $inventoryMovement);
        $inventoryMovement->delete();

        // Log the deletion of the inventory movement
        Log::info('Inventory movement deleted.', ['user_id' => Auth::id(), 'inventory_movement_id' => $inventoryMovement->id]);

        return redirect()->route('inventory-movements.index')->with('success', 'Inventory movement deleted successfully.');
    }
}
