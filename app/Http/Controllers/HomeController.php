<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured products or any other data needed for the homepage
        $featuredProducts = Product::where('is_featured', true)->take(6)->get();

        return view('pages.home', compact('featuredProducts'));
    }
}
