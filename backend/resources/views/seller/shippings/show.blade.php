@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Shipping Details</h1>
        <table class="table">
            <tr>
                <th>ID</th>
                <td>{{ $shipping->id }}</td>
            </tr>
            <tr>
                <th>Order ID</th>
                <td>{{ $shipping->order_id }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $shipping->address }}</td>
            </tr>
            <tr>
                <th>City</th>
                <td>{{ $shipping->city }}</td>
            </tr>
            <tr>
                <th>State</th>
                <td>{{ $shipping->state }}</td>
            </tr>
            <tr>
                <th>Postal Code</th>
                <td>{{ $shipping->postal_code }}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{ $shipping->country }}</td>
            </tr>
            <tr>
                <th>Shipping Method</th>
                <td>{{ $shipping->shipping_method }}</td>
            </tr>
            <tr>
                <th>Tracking Number</th>
                <td>{{ $shipping->tracking_number }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $shipping->status }}</td>
            </tr>
        </table>
        <a href="{{ route('shippings.edit', $shipping->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('shippings.destroy', $shipping->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection
