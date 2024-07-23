@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Customer Details</h1>
        <div class="card">
            <div class="card-header">
                {{ $customer->user->name }}
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Phone Number:</strong> {{ $customer->phone_number }}</p>
                <p><strong>Address:</strong> {{ $customer->address }}</p>
                <p><strong>Is Guest:</strong> {{ $customer->is_guest ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3">Back to Customers</a>
    </div>
@endsection
