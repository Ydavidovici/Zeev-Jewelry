@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Shipping</h1>
        <form action="{{ route('shippings.update', $shipping->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('seller.shippings.form')
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
