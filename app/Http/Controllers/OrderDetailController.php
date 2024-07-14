<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', OrderDetail::class);
        $orderDetails = OrderDetail::all();
        return view('order_details.index', compact('orderDetails'));
    }

    public function create()
    {
        $this->authorize('create', OrderDetail::class);
        return view('order_details.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', OrderDetail::class);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        OrderDetail::create($request->all());

        return redirect()->route('order-details.index');
    }

    public function show(OrderDetail $orderDetail)
    {
        $this->authorize('view', $orderDetail);
        return view('order_details.show', compact('orderDetail'));
    }

    public function edit(OrderDetail $orderDetail)
    {
        $this->authorize('update', $orderDetail);
        return view('order_details.edit', compact('orderDetail'));
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        $this->authorize('update', $orderDetail);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderDetail->update($request->all());

        return redirect()->route('order-details.index');
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $this->authorize('delete', $orderDetail);
        $orderDetail->delete();

        return redirect()->route('order-details.index');
    }
}
