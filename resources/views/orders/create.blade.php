@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Order</h1>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" id="status" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
