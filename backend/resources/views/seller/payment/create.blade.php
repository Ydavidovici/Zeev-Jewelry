@extends('layouts.app')

@section('content')
    <h1>Create Payment Record</h1>
    <form action="{{ route('seller.payments.store') }}" method="POST">
        @csrf
        <label>Order ID:</label>
        <input type="number" name="order_id">
        <label>Amount:</label>
        <input type="number" name="amount">
        <label>Status:</label>
        <input type="text" name="status">
        <button type="submit">Create</button>
    </form>
@endsection
