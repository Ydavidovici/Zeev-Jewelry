<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories.index');
    }
}
