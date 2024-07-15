@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Orders</h1>
        <ul>
            @foreach($orders as $order)
                <li>Order #{{ $order->id }} - <a href="{{ route('seller.orders.show', $order->id) }}">View</a></li>
            @endforeach
        </ul>
    </div>
@endsection
