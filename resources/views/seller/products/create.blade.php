@extends('layouts.app')

@section('content')
    <h1>Create Product</h1>
    <form action="{{ route('seller.products.store') }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="name">
        <label>Description:</label>
        <textarea name="description"></textarea>
        <label>Price:</label>
        <input type="number" name="price">
        <label>Stock Quantity:</label>
        <input type="number" name="stock_quantity">
        <label>Listed:</label>
        <input type="checkbox" name="listed">
        <button type="submit">Create</button>
    </form>
@endsection
