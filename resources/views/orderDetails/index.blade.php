@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Details</h1>
        <a href="{{ route('order-details.create') }}" class="btn btn-primary">Create Order Detail</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orderDetails as $orderDetail)
                <tr>
                    <td>{{ $orderDetail->id }}</td>
                    <td>{{ $orderDetail->order_id }}</td>
                    <td>{{ $orderDetail->product_id }}</td>
                    <td>{{ $orderDetail->quantity }}</td>
                    <td>{{ $orderDetail->price }}</td>
                    <td>
                        <a href="{{ route('order-details.show', $orderDetail->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('order-details.edit', $orderDetail->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('order-details.destroy', $orderDetail->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
