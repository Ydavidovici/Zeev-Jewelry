<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-white text-lg font-semibold">Zeev-Jewelry</a>
        <div>
            @if (Auth::check())
                <a href="{{ url('/admin') }}" class="text-gray-300 hover:text-white px-4">Admin Dashboard</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-300 hover:text-white px-4">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-4">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-gray-300 hover:text-white px-4">Register</a>
                @endif
            @endif
        </div>
    </div>
</nav>
