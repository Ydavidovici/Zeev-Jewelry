@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Review</h1>
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="number" name="product_id" id="product_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="number" name="customer_id" id="customer_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="review_text">Review Text</label>
                <textarea name="review_text" id="review_text" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>
            <div class="form-group">
                <label for="review_date">Review Date</label>
                <input type="date" name="review_date" id="review_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
