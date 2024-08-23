<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $this->authorize('accessDashboard', User::class);

        Log::channel('custom')->info('Admin accessing dashboard');

        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        $products = Product::all();
        $orders = Order::all();

        Log::channel('custom')->info('Admin dashboard data retrieved', compact('users', 'roles', 'permissions', 'products', 'orders'));

        return response()->json([
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'products' => $products,
            'orders' => $orders,
        ]);
    }
}
