<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-inventory', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventories = Auth::user()->hasRole('admin')
            ? Inventory::all()
            : Inventory::where('user_id', Auth::id())->get();

        return response()->json($inventories);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-inventory', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('view-inventory', $inventory)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($inventory);
    }

    public function update(Request $request, Inventory $inventory): JsonResponse
    {
        if (!Gate::allows('update-inventory', $inventory)) {
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
        if (!Gate::allows('delete-inventory', $inventory)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $inventory->delete();

        return response()->json(null, 204);
    }
}
