@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Shipping</h1>

        <form action="{{ route('admin.shipping.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="order_id">Order ID</label>
                <input type="text" name="order_id" class="form-control" value="{{ old('order_id') }}">
            </div>
            <div class="form-group">
                <label for="tracking_number">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number') }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="{{ old('status') }}">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
