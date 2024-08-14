<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Order::class);
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);
        return response()->json($order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);
        $order->delete();

        return response()->json(null, 204);
    }
}
