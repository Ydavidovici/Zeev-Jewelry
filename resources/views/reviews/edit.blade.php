@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Review</h1>
        <form action="{{ route('reviews.update', $review->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="number" name="product_id" id="product_id" class="form-control" value="{{ $review->product_id }}" required>
            </div>
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="number" name="customer_id" id="customer_id" class="form-control" value="{{ $review->customer_id }}" required>
            </div>
            <div class="form-group">
                <label for="review_text">Review Text</label>
                <textarea name="review_text" id="review_text" class="form-control" required>{{ $review->review_text }}</textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" value="{{ $review->rating }}" required>
            </div>
            <div class="form-group">
                <label for="review_date">Review Date</label>
                <input type="date" name="review_date" id="review_date" class="form-control" value="{{ $review->review_date }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
