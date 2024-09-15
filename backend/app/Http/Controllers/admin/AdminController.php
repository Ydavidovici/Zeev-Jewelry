<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function index(): JsonResponse
    {
        // Check if the authenticated user has the 'admin' role
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::all();
        $products = Product::all();
        $orders = Order::all();
        $categories = Category::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles->pluck('name'),
            'permissions' => $permissions->pluck('name'),
            'products' => $products,
            'orders' => $orders,
            'categories' => $categories,
        ]);
    }
}
