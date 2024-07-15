@extends('layouts.app')

@section('content')
    <h1>Edit Product</h1>
    <form action="{{ route('seller.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $product->name }}">
        <label>Description:</label>
        <textarea name="description">{{ $product->description }}</textarea>
        <label>Price:</label>
        <input type="number" name="price" value="{{ $product->price }}">
        <label>Stock Quantity:</label>
        <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}">
        <label>Listed:</label>
        <input type="checkbox" name="listed" {{ $product->listed ? 'checked' : '' }}>
        <button type="submit">Update</button>
    </form>
@endsection
