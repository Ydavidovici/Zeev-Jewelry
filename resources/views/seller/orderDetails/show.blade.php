@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Detail</h1>
        <p><strong>Order Detail ID:</strong> {{ $orderDetail->id }}</p>
        <p><strong>Order ID:</strong> {{ $orderDetail->order_id }}</p>
        <p><strong>Product ID:</strong> {{ $orderDetail->product_id }}</p>
        <p><strong>Quantity:</strong> {{ $orderDetail->quantity }}</p>
        <p><strong>Price:</strong> {{ $orderDetail->price }}</p>
        <a href="{{ route('order-details.edit', $orderDetail->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('order-details.destroy', $orderDetail->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('order-details.index') }}" class="btn btn-secondary">Back to Order Details</a>
    </div>
@endsection
