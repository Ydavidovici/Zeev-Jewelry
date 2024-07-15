@extends('layouts.app')

@section('content')
    <h1>Edit Payment Record</h1>
    <form action="{{ route('seller.payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Order ID:</label>
        <input type="number" name="order_id" value="{{ $payment->order_id }}">
        <label>Amount:</label>
        <input type="number" name="amount" value="{{ $payment->amount }}">
        <label>Status:</label>
        <input type="text" name="status" value="{{ $payment->status }}">
        <button type="submit">Update</button>
    </form>
@endsection
