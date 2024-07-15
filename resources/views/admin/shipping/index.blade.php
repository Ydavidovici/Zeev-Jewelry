@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Shipping</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary">Create Shipping</a>

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Tracking Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($shippings as $shipping)
                <tr>
                    <td>{{ $shipping->id }}</td>
                    <td>{{ $shipping->order_id }}</td>
                    <td>{{ $shipping->tracking_number }}</td>
                    <td>{{ $shipping->status }}</td>
                    <td>
                        <a href="{{ route('admin.shipping.show', $shipping->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('admin.shipping.edit', $shipping->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.shipping.destroy', $shipping->id) }}" method="POST" style="display: inline-block;">
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
