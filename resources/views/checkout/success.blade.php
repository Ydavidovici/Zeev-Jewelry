<!-- resources/views/checkout/success.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Placed Successfully</h1>
        <div class="alert alert-success">
            Your order has been placed successfully. Thank you for shopping with us!
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
@endsection
