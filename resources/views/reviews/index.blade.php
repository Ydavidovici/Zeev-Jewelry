@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reviews</h1>
        <a href="{{ route('reviews.create') }}" class="btn btn-primary">Create Review</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Customer ID</th>
                <th>Review Text</th>
                <th>Rating</th>
                <th>Review Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->product_id }}</td>
                    <td>{{ $review->customer_id }}</td>
                    <td>{{ $review->review_text }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ $review->review_date }}</td>
                    <td>
                        <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
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
