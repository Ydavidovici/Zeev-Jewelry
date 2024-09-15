<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        // Only admins can view all order details
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Only admins can create order details
        if (!$user->hasRole('admin')) {
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
        $user = auth()->user();

        // Only admins can view an order detail
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($orderDetail);
    }

    public function update(Request $request, OrderDetail $orderDetail): JsonResponse
    {
        $user = auth()->user();

        // Only admins can update an order detail
        if (!$user->hasRole('admin')) {
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
        $user = auth()->user();

        // Only admins can delete an order detail
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orderDetail->delete();

        return response()->json(null, 204);
    }
}
