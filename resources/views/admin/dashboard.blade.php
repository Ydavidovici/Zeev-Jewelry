@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container mx-auto py-6">
        <h1 class="text-4xl font-bold mb-4">Admin Dashboard</h1>
        <p class="text-lg mb-8">Welcome to the admin dashboard. Manage your site settings, users, and view analytics.</p>

        <!-- Add links to different admin functionalities -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.users.index') }}" class="bg-blue-500 text-white p-4 rounded-lg">Manage Users</a>
            <a href="{{ route('admin.categories.index') }}" class="bg-green-500 text-white p-4 rounded-lg">Manage Categories</a>
            <a href="{{ route('admin.products.index') }}" class="bg-yellow-500 text-white p-4 rounded-lg">Manage Products</a>
            <a href="{{ route('admin.orders.index') }}" class="bg-red-500 text-white p-4 rounded-lg">Manage Orders</a>
            <a href="{{ route('admin.settings') }}" class="bg-gray-500 text-white p-4 rounded-lg">Site Settings</a>
            <a href="{{ route('admin.analytics') }}" class="bg-indigo-500 text-white p-4 rounded-lg">View Analytics</a>
        </div>
    </div>
@endsection
