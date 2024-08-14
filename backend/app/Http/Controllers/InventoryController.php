<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Inventory::class);

        $inventories = Inventory::all();
        if (Auth::user()->hasRole('admin')) {
            $inventories = Inventory::all();
        } elseif (Auth::user()->hasRole('seller')) {
            $inventories = Inventory::where('user_id', Auth::id())->get();
        }

        return response()->json($inventories);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Inventory::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $inventory = new Inventory($request->all());
        $inventory->user_id = Auth::id();
        $inventory->save();

        return response()->json($inventory, 201);
    }

    public function show(Inventory $inventory): JsonResponse
    {
        $this->authorize('view', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($inventory);
    }

    public function update(Request $request, Inventory $inventory): JsonResponse
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

        return response()->json($inventory);
    }

    public function destroy(Inventory $inventory): JsonResponse
    {
        $this->authorize('delete', $inventory);

        if (Auth::user()->hasRole('seller') && $inventory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $inventory->delete();

        return response()->json(null, 204);
    }
}
