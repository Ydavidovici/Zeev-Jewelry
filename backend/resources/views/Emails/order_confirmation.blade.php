<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body>
<h1>Order Confirmation - Order #{{ $order->id }}</h1>
<p>Thank you for your purchase, {{ $order->user->name }}!</p>
<p>Your order details:</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->product_name }} - {{ $item->quantity }} x ${{ $item->price }}</li>
    @endforeach
</ul>
<p>Total: ${{ $order->total }}</p>
</body>
</html>
