<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::all();
        return response()->json($reviews);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Review::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'review_text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'review_date' => 'required|date',
        ]);

        $review = Review::create($request->all());

        return response()->json($review, 201);
    }

    public function show(Review $review): JsonResponse
    {
        $this->authorize('view', $review);
        return response()->json($review);
    }

    public function update(Request $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'review_text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'review_date' => 'required|date',
        ]);

        $review->update($request->all());

        return response()->json($review);
    }

    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);
        $review->delete();

        return response()->json(null, 204);
    }
}
