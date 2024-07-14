<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;

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
        ]);

        InventoryMovement::create($request->all());

        return redirect()->route('inventory-movements.index');
    }

    public function show(InventoryMovement $inventoryMovement)
    {
        $this->authorize('view', $inventoryMovement);
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
        ]);

        $inventoryMovement->update($request->all());

        return redirect()->route('inventory-movements.index');
    }

    public function destroy(InventoryMovement $inventoryMovement)
    {
        $this->authorize('delete', $inventoryMovement);
        $inventoryMovement->delete();

        return redirect()->route('inventory-movements.index');
    }
}
