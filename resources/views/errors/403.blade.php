<!-- resources/views/errors/403.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>403 - Unauthorized</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
@endsection
