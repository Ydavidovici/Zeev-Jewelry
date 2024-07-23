<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::user()->hasRole('admin')) {
            $inventories = Inventory::all();
        } elseif (Auth::user()->hasRole('seller')) {
            $inventories = Inventory::where('user_id', Auth::id())->get(); // Assuming `user_id` tracks the inventory owner
        }

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

        $inventory = new Inventory($request->all());
        $inventory->user_id = Auth::id(); // Track the owner of the inventory
        $inventory->save();

        return redirect()->route('inventories.index')->with('success', 'Inventory added successfully');
    }

    public function show(Inventory $inventory)
    {
        $this->authorize('view', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        $this->authorize('update', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->authorize('update', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')->with('success', 'Inventory updated successfully');
    }

    public function destroy(Inventory $inventory)
    {
        $this->authorize('delete', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Inventory deleted successfully');
    }
}
