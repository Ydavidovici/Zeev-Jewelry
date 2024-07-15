@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Order</h1>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="order_number">Order Number</label>
                <input type="text" name="order_number" class="form-control" value="{{ $order->order_number }}" readonly>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="text" name="total_amount" class="form-control" value="{{ $order->total_amount }}" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>
    </div>
@endsection
