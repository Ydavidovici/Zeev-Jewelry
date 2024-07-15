@extends('layouts.app')

@section('content')
    <h1>Create Shipping</h1>
    <form action="{{ route('seller.shipping.store') }}" method="POST">
        @csrf
        <label>Order ID:</label>
        <input type="number" name="order_id">
        <label>Tracking Number:</label>
        <input type="text" name="tracking_number">
        <label>Status:</label>
        <input type="text" name="status">
        <button type="submit">Create</button>
    </form>
@endsection
