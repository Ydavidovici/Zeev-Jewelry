<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-review', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reviews = Review::all();
        return response()->json($reviews);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-review', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('view-review', $review)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($review);
    }

    public function update(Request $request, Review $review): JsonResponse
    {
        if (!Gate::allows('update-review', $review)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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
        if (!Gate::allows('delete-review', $review)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json(null, 204);
    }
}
