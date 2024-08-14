<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderDetailController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', OrderDetail::class);
        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', OrderDetail::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderDetail = OrderDetail::create($request->all());

        return response()->json($orderDetail, 201);
    }

    public function show(OrderDetail $orderDetail): JsonResponse
    {
        $this->authorize('view', $orderDetail);
        return response()->json($orderDetail);
    }

    public function update(Request $request, OrderDetail $orderDetail): JsonResponse
    {
        $this->authorize('update', $orderDetail);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderDetail->update($request->all());

        return response()->json($orderDetail);
    }

    public function destroy(OrderDetail $orderDetail): JsonResponse
    {
        $this->authorize('delete', $orderDetail);
        $orderDetail->delete();

        return response()->json(null, 204);
    }
}
