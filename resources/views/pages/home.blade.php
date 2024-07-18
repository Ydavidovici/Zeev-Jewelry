@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="hero-section my-5">
            <h1 class="text-center">Welcome to Zeev Jewelry</h1>
            <p class="text-center">Discover our exquisite collection of jewelry, crafted with passion and precision.</p>
            <div class="text-center">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now</a>
            </div>
        </div>

        <div class="featured-products my-5">
            <h2>Featured Products</h2>
            <div class="row">
                <!-- Assuming $featuredProducts is passed from the controller -->
                @foreach($featuredProducts as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">${{ $product->price }}</p>
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">View Product</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="call-to-action my-5">
            <h2>Why Choose Us?</h2>
            <p>At Zeev Jewelry, we offer the finest quality products with a commitment to customer satisfaction.</p>
            <div class="text-center">
                <a href="{{ route('about') }}" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </div>
@endsection
