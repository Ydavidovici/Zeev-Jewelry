<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\ShippingConfirmationMail;
use App\Mail\DeliveryConfirmationMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-order', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-order', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
            'payment_intent_id' => 'nullable|string|max:255',
        ]);

        $order = Order::create($request->all());

        Mail::to($order->customer->email)->send(new OrderConfirmationMail($order));

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        if (!Gate::allows('view-order', $order)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        if (!Gate::allows('update-order', $order)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
            'payment_intent_id' => 'nullable|string|max:255',
        ]);

        $order->update($request->all());

        if ($order->status === 'shipped') {
            Mail::to($order->customer->email)->send(new ShippingConfirmationMail($order));
        } elseif ($order->status === 'delivered') {
            Mail::to($order->customer->email)->send(new DeliveryConfirmationMail($order));
        }

        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        if (!Gate::allows('delete-order', $order)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->delete();

        return response()->json(null, 204);
    }
}
