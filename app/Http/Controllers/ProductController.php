<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Product::class);
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()->route('products.index');
    }
}
