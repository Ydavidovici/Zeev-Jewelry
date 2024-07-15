@extends('layouts.app')

@section('content')
    <h1>Shipping</h1>
    <a href="{{ route('seller.shipping.create') }}">Create Shipping</a>
    <ul>
        @foreach ($shippings as $shipping)
            <li>
                Order ID: {{ $shipping->order_id }} - {{ $shipping->tracking_number }} - {{ $shipping->status }}
                <a href="{{ route('seller.shipping.edit', $shipping->id) }}">Edit</a>
                <form action="{{ route('seller.shipping.destroy', $shipping->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
