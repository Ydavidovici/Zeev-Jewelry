<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function show(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // Track recently viewed products
        $viewedProducts = json_decode($request->cookie('viewed_products', '[]'), true);

        if (!in_array($id, $viewedProducts)) {
            $viewedProducts[] = $id;
            cookie()->queue(cookie('viewed_products', json_encode($viewedProducts), 60 * 24 * 7)); // 7 days
        }

        return response()->json(['product' => $product]);
    }

    public function showRecentlyViewed(Request $request): JsonResponse
    {
        $viewedProducts = json_decode($request->cookie('viewed_products', '[]'), true);

        $products = Product::whereIn('id', $viewedProducts)->get();

        return response()->json(['recently_viewed' => $products]);
    }
}
