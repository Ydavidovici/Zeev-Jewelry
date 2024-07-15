@extends('layouts.app')

@section('content')
    <h1>Edit Inventory</h1>
    <form action="{{ route('seller.inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Product:</label>
        <select name="product_id">
            @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ $inventory->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
        <label>Quantity:</label>
        <input type="number" name="quantity" value="{{ $inventory->quantity }}">
        <label>Location:</label>
        <input type="text" name="location" value="{{ $inventory->location }}">
        <button type="submit">Update</button>
    </form>
@endsection
