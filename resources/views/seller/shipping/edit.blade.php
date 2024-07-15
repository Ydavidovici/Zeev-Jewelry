@extends('layouts.app')

@section('content')
    <h1>Edit Shipping</h1>
    <form action="{{ route('seller.shipping.update', $shipping->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Order ID:</label>
        <input type="number" name="order_id" value="{{ $shipping->order_id }}">
        <label>Tracking Number:</label>
        <input type="text" name="tracking_number" value="{{ $shipping->tracking_number }}">
        <label>Status:</label>
        <input type="text" name="status" value="{{ $shipping->status }}">
        <button type="submit">Update</button>
    </form>
@endsection
