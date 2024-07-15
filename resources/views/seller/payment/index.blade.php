@extends('layouts.app')

@section('content')
    <h1>Payments</h1>
    <a href="{{ route('seller.payments.create') }}">Create Payment Record</a>
    <ul>
        @foreach ($payments as $payment)
            <li>
                Order ID: {{ $payment->order_id }} - Amount: {{ $payment->amount }} - Status: {{ $payment->status }}
                <a href="{{ route('seller.payments.edit', $payment->id) }}">Edit</a>
                <form action="{{ route('seller.payments.destroy', $payment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
