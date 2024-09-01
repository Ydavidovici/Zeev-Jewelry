<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Show a single product by ID
    public function show(Request $request, $id): JsonResponse
    {
        if (!Gate::allows('view-product', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = Product::findOrFail($id);

        $viewedProducts = json_decode($request->cookie('viewed_products', '[]'), true);

        if (!in_array($id, $viewedProducts)) {
            $viewedProducts[] = $id;
            cookie()->queue(cookie('viewed_products', json_encode($viewedProducts), 60 * 24 * 7)); // 7 days
        }

        return response()->json(['product' => $product]);
    }

    // Show recently viewed products
    public function showRecentlyViewed(Request $request): JsonResponse
    {
        if (!Gate::allows('view-recently-viewed-products', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $viewedProducts = json_decode($request->cookie('viewed_products', '[]'), true);

        $products = Product::whereIn('id', $viewedProducts)->get();

        return response()->json(['recently_viewed' => $products]);
    }

    // Create a new product
    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-product', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json(['product' => $product], 201);
    }

    // Update an existing product
    public function update(Request $request, Product $product): JsonResponse
    {
        if (!Gate::allows('update-product', $product)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'is_featured' => 'sometimes|boolean',
        ]);

        $product->update($request->all());

        return response()->json(['product' => $product]);
    }

    // Delete a product
    public function destroy(Product $product): JsonResponse
    {
        if (!Gate::allows('delete-product', $product)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    // List all products (optional, depending on your needs)
    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-product', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $products = Product::all();

        return response()->json(['products' => $products]);
    }
}
