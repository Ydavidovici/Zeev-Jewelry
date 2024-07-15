@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Permission</h1>

        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $permission->name }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
