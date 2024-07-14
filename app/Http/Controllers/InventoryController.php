<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Inventory::class);
        $inventories = Inventory::all();
        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        $this->authorize('create', Inventory::class);
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Inventory::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventories.index');
    }

    public function show(Inventory $inventory)
    {
        $this->authorize('view', $inventory);
        return view('inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        $this->authorize('update', $inventory);
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->authorize('update', $inventory);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index');
    }

    public function destroy(Inventory $inventory)
    {
        $this->authorize('delete', $inventory);
        $inventory->delete();

        return redirect()->route('inventories.index');
    }
}
