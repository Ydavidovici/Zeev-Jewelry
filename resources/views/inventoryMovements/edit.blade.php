@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Inventory Movement</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventory-movements.update', $inventoryMovement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="inventory_id">Inventory ID</label>
                <input type="text" name="inventory_id" id="inventory_id" class="form-control" value="{{ old('inventory_id', $inventoryMovement->inventory_id) }}">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $inventoryMovement->quantity) }}">
            </div>
            <div class="form-group">
                <label for="movement_type">Movement Type</label>
                <input type="text" name="movement_type" id="movement_type" class="form-control" value="{{ old('movement_type', $inventoryMovement->movement_type) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
