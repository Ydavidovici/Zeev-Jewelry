<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/main.js') }}" defer></script>
</head>
<body>
<div class="admin-dashboard">
    @include('admin.partials.sidebar')
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
