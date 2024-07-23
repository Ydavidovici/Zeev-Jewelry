@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Shipping List</h1>
        <a href="{{ route('shippings.create') }}" class="btn btn-primary">Create Shipping</a>
        <table class="table mt-3">
            <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Postal Code</th>
                <th>Country</th>
                <th>Shipping Method</th>
                <th>Tracking Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($shippings as $shipping)
                <tr>
                    <td>{{ $shipping->id }}</td>
                    <td>{{ $shipping->order_id }}</td>
                    <td>{{ $shipping->address }}</td>
                    <td>{{ $shipping->city }}</td>
                    <td>{{ $shipping->state }}</td>
                    <td>{{ $shipping->postal_code }}</td>
                    <td>{{ $shipping->country }}</td>
                    <td>{{ $shipping->shipping_method }}</td>
                    <td>{{ $shipping->tracking_number }}</td>
                    <td>{{ $shipping->status }}</td>
                    <td>
                        <a href="{{ route('shippings.show', $shipping->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('shippings.edit', $shipping->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('shippings.destroy', $shipping->id) }}" method="POST" style="display:inline;">
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
