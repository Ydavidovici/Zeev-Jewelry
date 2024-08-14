<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shipping Confirmation</title>
</head>
<body>
<h1>Shipping Confirmation - Order #{{ $order->id }}</h1>
<p>Hi {{ $order->user->name }},</p>
<p>Your order has been shipped! Here are the details:</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<p>Tracking Number: {{ $order->tracking_number }}</p>
<p>Total: ${{ $order->total }}</p>
</body>
</html>
