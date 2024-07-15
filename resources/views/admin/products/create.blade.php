@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" value="{{ old('price') }}">
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="text" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}">
            </div>
            <div class="form-group">
                <label for="listed">Listed</label>
                <input type="checkbox" name="listed" value="1">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
