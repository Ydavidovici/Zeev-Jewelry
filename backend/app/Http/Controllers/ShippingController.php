<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'shipping_method' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = Shipping::create($request->all());

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
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'shipping_method' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
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
}
