@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Orders</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Create Order</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>{{ $order->total_amount }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
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
