@extends('layouts.app')

@section('content')
    <h1>Add to Inventory</h1>
    <form action="{{ route('seller.inventory.store') }}" method="POST">
        @csrf
        <label>Product:</label>
        <select name="product_id">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <label>Quantity:</label>
        <input type="number" name="quantity">
        <label>Location:</label>
        <input type="text" name="location">
        <button type="submit">Add</button>
    </form>
@endsection
