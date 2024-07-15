@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container mx-auto py-6">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome to Zeev-Jewelry</h1>
            <p class="text-lg mb-8">Explore our exclusive collection of jewelry and find the perfect piece for you.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Example product cards -->
            <div class="border rounded-lg p-4">
                <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full mb-4">
                <h2 class="text-2xl font-semibold mb-2">Product Name</h2>
                <p class="text-gray-700 mb-4">Short description of the product goes here.</p>
                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded">View Product</a>
            </div>

            <div class="border rounded-lg p-4">
                <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full mb-4">
                <h2 class="text-2xl font-semibold mb-2">Product Name</h2>
                <p class="text-gray-700 mb-4">Short description of the product goes here.</p>
                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded">View Product</a>
            </div>

            <div class="border rounded-lg p-4">
                <img src="https://via.placeholder.com/150" alt="Product Image" class="w-full mb-4">
                <h2 class="text-2xl font-semibold mb-2">Product Name</h2>
                <p class="text-gray-700 mb-4">Short description of the product goes here.</p>
                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded">View Product</a>
            </div>
        </div>
    </div>
@endsection
