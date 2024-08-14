@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <img src="{{ asset('path_to_product_image') }}" alt="{{ $product->name }}" class="img-fluid">
        <p>{{ $product->description }}</p>
        <p>Price: ${{ $product->price }}</p>
        <p>Stock: {{ $product->stock_quantity }}</p>
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
            </div>
            <button type="submit" class="btn btn-primary">Add to Cart</button>
        </form>
    </div>
@endsection
