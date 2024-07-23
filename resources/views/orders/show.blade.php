@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Details</h1>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
        <p><strong>Order Date:</strong> {{ $order->order_date }}</p>
        <p><strong>Total Amount:</strong> {{ $order->total_amount }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
@endsection
