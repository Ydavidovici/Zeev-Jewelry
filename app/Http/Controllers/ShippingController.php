<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Shipping::class);
        $shippings = Shipping::all();
        return view('shippings.index', compact('shippings'));
    }

    public function create()
    {
        $this->authorize('create', Shipping::class);
        return view('shippings.create');
    }

    public function store(Request $request)
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

        Shipping::create($request->all());

        return redirect()->route('shippings.index');
    }

    public function show(Shipping $shipping)
    {
        $this->authorize('view', $shipping);
        return view('shipping.show', compact('shipping'));
    }

    public function edit(Shipping $shipping)
    {
        $this->authorize('update', $shipping);
        return view('shippings.edit', compact('shipping'));
    }

    public function update(Request $request, Shipping $shipping)
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

        return redirect()->route('shippings.index');
    }

    public function destroy(Shipping $shipping)
    {
        $this->authorize('delete', $shipping);
        $shipping->delete();

        return redirect()->route('shippings.index');
    }
}
