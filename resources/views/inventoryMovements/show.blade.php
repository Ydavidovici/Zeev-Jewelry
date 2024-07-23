@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Inventory Movement Details</h1>

        <table class="table">
            <tr>
                <th>Inventory ID</th>
                <td>{{ $inventoryMovement->inventory_id }}</td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td>{{ $inventoryMovement->quantity }}</td>
            </tr>
            <tr>
                <th>Movement Type</th>
                <td>{{ $inventoryMovement->movement_type }}</td>
            </tr>
        </table>

        <a href="{{ route('inventory-movements.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
