@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Uploaded Files</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>File</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($fileUrls as $fileUrl)
                <tr>
                    <td><a href="{{ $fileUrl }}" target="_blank">{{ basename($fileUrl) }}</a></td>
                    <td>
                        <form action="{{ route('file.delete', basename($fileUrl)) }}" method="POST">
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
