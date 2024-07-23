<!-- resources/views/checkout/failure.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Placement Failed</h1>
        <div class="alert alert-danger">
            An error occurred while placing your order. Please try again.
        </div>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary">Go Back to Checkout</a>
    </div>
@endsection
