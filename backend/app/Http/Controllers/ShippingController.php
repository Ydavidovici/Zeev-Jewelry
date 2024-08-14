<?php

namespace App\Http\Controllers;

use App\Mail\ShippingConfirmationMail;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ShippingController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Shipping::class);
        $shippings = Shipping::all();
        return response()->json($shippings);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Shipping::class);

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

        // Send shipping confirmation email
        $order = $shipping->order;
        Mail::to($order->customer->email)->send(new ShippingConfirmationMail($order));

        return response()->json($shipping, 201);
    }

    public function show(Shipping $shipping): JsonResponse
    {
        $this->authorize('view', $shipping);
        return response()->json($shipping);
    }

    public function update(Request $request, Shipping $shipping): JsonResponse
    {
        $this->authorize('update', $shipping);

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
        $this->authorize('delete', $shipping);
        $shipping->delete();

        return response()->json(null, 204);
    }

    public function track(Shipping $shipping): JsonResponse
    {
        $this->authorize('view', $shipping);

        try {
            $trackingInfo = $shipping->trackShipment();
            return response()->json($trackingInfo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
