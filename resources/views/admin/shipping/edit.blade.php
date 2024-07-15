@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Shipping</h1>

        <form action="{{ route('admin.shipping.update', $shipping->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="order_id">Order ID</label>
                <input type="text" name="order_id" class="form-control" value="{{ $shipping->order_id }}">
            </div>
            <div class="form-group">
                <label for="tracking_number">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control" value="{{ $shipping->tracking_number }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="{{ $shipping->status }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
