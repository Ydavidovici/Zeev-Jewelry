<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Review::class);
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $this->authorize('create', Review::class);
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Review::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'review_text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'review_date' => 'required|date',
        ]);

        Review::create($request->all());

        return redirect()->route('reviews.index');
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
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

        return redirect()->route('reviews.index');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();

        return redirect()->route('reviews.index');
    }
}
