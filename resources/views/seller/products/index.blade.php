@extends('layouts.app')

@section('content')
    <h1>Products</h1>
    <a href="{{ route('seller.products.create') }}">Create New Product</a>
    <ul>
        @foreach ($products as $product)
            <li>
                {{ $product->name }} - {{ $product->price }}
                <a href="{{ route('seller.products.edit', $product->id) }}">Edit</a>
                <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
