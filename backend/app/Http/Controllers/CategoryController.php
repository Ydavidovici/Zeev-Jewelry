<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        if (!Gate::allows('view-any-category', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Gate::allows('create-category', auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        if (!Gate::allows('view-category', $category, auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        if (!Gate::allows('update-category', $category, auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        if (!Gate::allows('delete-category', $category, auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
