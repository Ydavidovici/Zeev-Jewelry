<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Order::class);
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
        ]);

        Order::create($request->all());

        return redirect()->route('orders.index');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
        ]);

        $order->update($request->all());

        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();

        return redirect()->route('orders.index');
    }
}
