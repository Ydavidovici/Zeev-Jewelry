@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Product</h1>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ $product->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" value="{{ $product->price }}">
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="text" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}">
            </div>
            <div class="form-group">
                <label for="listed">Listed</label>
                <input type="checkbox" name="listed" value="1" {{ $product->listed ? 'checked' : '' }}>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
