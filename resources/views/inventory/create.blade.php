@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add to Inventory</h1>

        <form action="{{ route('admin.inventory.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" class="form-control">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" name="quantity" class="form-control" value="{{ old('quantity') }}">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>
            <button type="submit" class="btn btn-primary">Add to Inventory</button>
        </form>
    </div>
@endsection
