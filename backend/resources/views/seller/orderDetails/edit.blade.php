@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Order Detail</h1>
        <form action="{{ route('order-details.update', $orderDetail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="order_id">Order ID</label>
                <input type="number" name="order_id" id="order_id" class="form-control" value="{{ $orderDetail->order_id }}" required>
            </div>
            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="number" name="product_id" id="product_id" class="form-control" value="{{ $orderDetail->product_id }}" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $orderDetail->quantity }}" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ $orderDetail->price }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
