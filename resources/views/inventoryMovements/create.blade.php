@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Inventory Movement</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventory-movements.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="inventory_id">Inventory ID</label>
                <input type="text" name="inventory_id" id="inventory_id" class="form-control" value="{{ old('inventory_id') }}">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
            </div>
            <div class="form-group">
                <label for="movement_type">Movement Type</label>
                <input type="text" name="movement_type" id="movement_type" class="form-control" value="{{ old('movement_type') }}">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
