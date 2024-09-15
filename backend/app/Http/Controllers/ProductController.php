<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Show a single product by ID
    public function show(Request $request, $id): JsonResponse
    {
        if (!auth()->user()->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = Product::findOrFail($id);

        // Logic for recently viewed products
        $viewedProducts = json_decode($request->cookie('viewed_products', '[]'), true);

        if (!in_array($id, $viewedProducts)) {
            $viewedProducts[] = $id;
            cookie()->queue(cookie('viewed_products', json_encode($viewedProducts), 60 * 24 * 7)); // 7 days
        }

        return response()->json(['product' => $product]);
    }

    // Create a new product
    public function store(Request $request): JsonResponse
    {
        if (!auth()->user()->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0', // Change from 'quantity' to 'stock_quantity'
            'is_featured' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json(['product' => $product], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        if (!auth()->user()->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0', // Change from 'quantity' to 'stock_quantity'
            'is_featured' => 'sometimes|boolean',
        ]);

        $product->update($request->all());

        return response()->json(['product' => $product]);
    }

    // Delete a product
    public function destroy(Product $product): JsonResponse
    {
        if (!auth()->user()->hasRole('seller')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
