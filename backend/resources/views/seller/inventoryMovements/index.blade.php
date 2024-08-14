@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Inventory Movements</h1>

        <a href="{{ route('inventory-movements.create') }}" class="btn btn-primary mb-3">Add Inventory Movement</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($inventoryMovements->count() > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Inventory ID</th>
                    <th>Quantity</th>
                    <th>Movement Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($inventoryMovements as $movement)
                    <tr>
                        <td>{{ $movement->inventory_id }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->movement_type }}</td>
                        <td>
                            <a href="{{ route('inventory-movements.edit', $movement->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('inventory-movements.destroy', $movement->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>No inventory movements found.</p>
        @endif
    </div>
@endsection
