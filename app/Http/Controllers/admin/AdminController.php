<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    // Show the admin-page dashboard
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        $products = Product::all();
        $orders = Order::all();
        return view('admin.dashboard', compact('users', 'roles', 'permissions', 'products', 'orders'));
    }
}
