@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Order</h1>
        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @if ($order->customer_id == $customer->id) selected @endif>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" class="form-control" value="{{ $order->order_date }}">
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" value="{{ $order->total_amount }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" id="status" class="form-control" value="{{ $order->status }}">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
