@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Review</h1>
        <p><strong>Review ID:</strong> {{ $review->id }}</p>
        <p><strong>Product ID:</strong> {{ $review->product_id }}</p>
        <p><strong>Customer ID:</strong> {{ $review->customer_id }}</p>
        <p><strong>Review Text:</strong> {{ $review->review_text }}</p>
        <p><strong>Rating:</strong> {{ $review->rating }}</p>
        <p><strong>Review Date:</strong> {{ $review->review_date }}</p>
        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
    </div>
@endsection
