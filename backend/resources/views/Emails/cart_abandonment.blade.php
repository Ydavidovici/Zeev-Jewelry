<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Don't Forget Your Items!</title>
</head>
<body>
<h1>You Left Something Behind</h1>
<p>Hi {{ $user->name }},</p>
<p>It looks like you left some items in your cart. Don't miss out! Complete your purchase now:</p>
<ul>
    @foreach ($cart->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<a href="{{ url('cart') }}">Complete Your Purchase</a>
</body>
</html>
