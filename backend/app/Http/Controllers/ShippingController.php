<?php

namespace App\Http\Controllers;

use App\Mail\ShippingConfirmationMail;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-shipping', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shippings = Shipping::all();
        return response()->json($shippings);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-shipping', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'seller_id' => 'required|exists:sellers,id',
            'shipping_type' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_status' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_carrier' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
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
        if (!Gate::allows('view-shipping', $shipping)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($shipping);
    }

    public function update(Request $request, Shipping $shipping): JsonResponse
    {
        if (!Gate::allows('update-shipping', $shipping)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'seller_id' => 'required|exists:sellers,id',
            'shipping_type' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_status' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_carrier' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'estimated_delivery_date' => 'nullable|date',
            'additional_notes' => 'nullable|string',
        ]);

        $shipping->update($request->all());

        return response()->json($shipping);
    }

    public function destroy(Shipping $shipping): JsonResponse
    {
        if (!Gate::allows('delete-shipping', $shipping)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shipping->delete();

        return response()->json(null, 204);
    }

    public function track(Shipping $shipping): JsonResponse
    {
        if (!Gate::allows('view-shipping', $shipping)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $trackingInfo = $shipping->trackShipment();
            return response()->json($trackingInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
