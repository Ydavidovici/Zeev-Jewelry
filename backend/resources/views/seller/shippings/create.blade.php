@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Shipping</h1>
        <form action="{{ route('shippings.store') }}" method="POST">
            @csrf
            @include('shippings.form')
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
