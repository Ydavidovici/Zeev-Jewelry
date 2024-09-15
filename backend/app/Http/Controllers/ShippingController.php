<?php

namespace App\Http\Controllers;

use App\Mail\ShippingConfirmationMail;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        // Role-based authorization: Check if the user is either an admin or a seller
        if (!$user->hasRole(['admin', 'seller'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shippings = Shipping::all();
        return response()->json($shippings);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Role-based authorization: Only sellers can create shipping details
        if (!$user->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'seller_id' => 'required|exists:users,id',
            'shipping_type' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_status' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_carrier' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'shipping_method' => 'required|string|max:255',  // Add this line
            'estimated_delivery_date' => 'nullable|date',
            'additional_notes' => 'nullable|string',
        ]);


        $shipping = Shipping::create($request->all());

        $order = $shipping->order;
        Mail::to($order->customer->email)->send(new ShippingConfirmationMail($order));

        return response()->json($shipping, 201);
    }

    public function show(Shipping $shipping): JsonResponse
    {
        $user = auth()->user();

        // Authorization: Admins can view any shipping, sellers can view their own
        if (!$user->hasRole('admin') && $user->id !== $shipping->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($shipping);
    }

    public function update(Request $request, Shipping $shipping): JsonResponse
    {
        // Validate the input
        $request->validate([
            'shipping_status' => 'required|string|max:255',
            // Add other fields you want to update, if any
        ]);

        // Update the shipping details
        $shipping->update($request->only(['shipping_status']));

        return response()->json($shipping);
    }

    public function destroy(Shipping $shipping): JsonResponse
    {
        $user = auth()->user();

        // Authorization: Only the seller of the shipping can delete it
        if (!$user->hasRole('admin') && $user->id !== $shipping->seller_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shipping->delete();

        return response()->json(null, 204);
    }
}
