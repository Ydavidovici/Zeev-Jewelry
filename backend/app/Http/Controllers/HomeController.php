<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $featuredProducts = Product::where('is_featured', true)->take(6)->get();

        return response()->json(['featured_products' => $featuredProducts]);
    }
}
