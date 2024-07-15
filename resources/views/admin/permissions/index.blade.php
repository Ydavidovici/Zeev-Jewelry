@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Permissions</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Create Permission</a>

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <a href="{{ route('admin.permissions.show', $permission->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
