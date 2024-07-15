@extends('layouts.app')

@section('content')
    <h1>Inventory</h1>
    <a href="{{ route('seller.inventory.add') }}">Add to Inventory</a>
    <ul>
        @foreach ($inventory as $item)
            <li>
                {{ $item->product->name }} - {{ $item->quantity }}
                <a href="{{ route('seller.inventory.edit', $item->id) }}">Edit</a>
                <form action="{{ route('seller.inventory.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
