<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('admin') && !$user->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orders = $user->hasRole('admin') ? Order::all() : Order::where('seller_id', $user->id)->get();

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Customers and sellers can create orders
        if (!$user->hasRole('customer') && !$user->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string',
        ]);

        // Assign seller or customer based on the user's role
        $validatedData['seller_id'] = $user->hasRole('seller') ? $user->id : null;

        $order = Order::create($validatedData);

        // Send order confirmation email
        Mail::to($order->customer->email)->send(new OrderConfirmationMail($order));

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        $user = Auth::user();

        // Only allow access to sellers who created the order or admins
        if (!$user->hasRole('admin') && $user->id !== $order->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller can update the order
        if (!$user->hasRole('admin') && $user->id !== $order->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|string',
        ]);

        $order->update($validatedData);

        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $user = Auth::user();

        // Only admins or the seller can delete the order
        if (!$user->hasRole('admin') && $user->id !== $order->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->delete();

        return response()->json(null, 204);
    }
}
