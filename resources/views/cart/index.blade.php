@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Shopping Cart</h1>
        @if ($cart && $cart->items->count() > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cart->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->product->price }}</td>
                        <td>${{ $item->product->price * $item->quantity }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <h3>Total: ${{ $cart->items->sum(fn($item) => $item->product->price * $item->quantity) }}</h3>
            <a href="{{ route('checkout.index') }}" class="btn btn-success">Proceed to Checkout</a>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
@endsection
