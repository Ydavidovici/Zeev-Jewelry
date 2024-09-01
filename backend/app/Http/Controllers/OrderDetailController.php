<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-order-detail', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-order-detail', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('view-order-detail', $orderDetail)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($orderDetail);
    }

    public function update(Request $request, OrderDetail $orderDetail): JsonResponse
    {
        if (!Gate::allows('update-order-detail', $orderDetail)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('delete-order-detail', $orderDetail)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orderDetail->delete();

        return response()->json(null, 204);
    }
}
