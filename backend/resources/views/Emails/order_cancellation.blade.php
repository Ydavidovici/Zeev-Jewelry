<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Cancellation</title>
</head>
<body>
<h1>Order Cancellation - Order #{{ $order->id }}</h1>
<p>Hi {{ $order->user->name }},</p>
<p>Your order has been canceled. Here are the details:</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<p>Total Refunded: ${{ $order->total }}</p>
</body>
</html>
